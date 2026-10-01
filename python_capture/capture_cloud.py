# coding=utf-8
# NetSentinel Railway-Compatible Capture Engine
# Uses Linux /proc/net/tcp instead of Scapy (no raw socket privileges needed)

import time
import requests
import threading
import subprocess
import os
import sys
import socket
import struct
from datetime import datetime

LARAVEL_API_URL = os.environ.get("LARAVEL_API_URL", "http://127.0.0.1:8000/api")
SETTINGS = {'realtime_alerts': True, 'port_scan': True, 'flooding': True}
WATCHLIST_IPS = []
DISCOVERED_DEVICES = {}
PORT_SCAN_TRACKER = {}  # {ip: [ports]}
PORT_SCAN_THRESHOLD = 10
TIME_WINDOW = 10
KNOWN_CONNECTIONS = set()

def hex_to_ip(hex_str):
    try:
        addr = int(hex_str, 16)
        return socket.inet_ntoa(struct.pack("<I", addr))
    except:
        return "0.0.0.0"

def hex_to_port(hex_str):
    try:
        return int(hex_str, 16)
    except:
        return 0

def read_proc_tcp():
    connections = []
    for path in ["/proc/net/tcp", "/proc/net/tcp6"]:
        try:
            with open(path, "r") as f:
                lines = f.readlines()[1:]
            for line in lines:
                parts = line.split()
                if len(parts) < 4:
                    continue
                local = parts[1].split(":")
                remote = parts[2].split(":")
                local_ip = hex_to_ip(local[0])
                local_port = hex_to_port(local[1])
                remote_ip = hex_to_ip(remote[0])
                remote_port = hex_to_port(remote[1])
                state = parts[3]
                connections.append((local_ip, local_port, remote_ip, remote_port, state))
        except:
            pass
    return connections

def send_threat(attack_type, severity, attacker_ip, victim_ip, description):
    try:
        requests.post(f"{LARAVEL_API_URL}/threats", json={
            'attack_type': attack_type,
            'severity': severity,
            'attacker_ip': attacker_ip,
            'victim_ip': victim_ip,
            'attacker_mac': 'N/A (Cloud Mode)',
            'description': description
        }, timeout=3)
        print(f"[!] {severity} THREAT: {attack_type} from {attacker_ip}")
    except Exception as e:
        print(f"[ERR] Could not send threat: {e}")

def auto_ban(ip, reason):
    if ip not in WATCHLIST_IPS and ip not in ['0.0.0.0', '127.0.0.1', '::1']:
        try:
            requests.get(f"{LARAVEL_API_URL}/watchlist/auto/{ip}/{reason}", timeout=2)
            WATCHLIST_IPS.append(ip)
            print(f"[BAN] Auto-banned {ip}: {reason}")
        except:
            pass

def sync_settings():
    global SETTINGS, WATCHLIST_IPS
    while True:
        try:
            resp = requests.get(f"{LARAVEL_API_URL}/settings", timeout=3)
            if resp.status_code == 200:
                SETTINGS = resp.json()
            wl = requests.get(f"{LARAVEL_API_URL}/watchlist", timeout=3)
            if wl.status_code == 200:
                WATCHLIST_IPS = wl.json()
        except:
            pass
        time.sleep(5)

def analyze_connections():
    global PORT_SCAN_TRACKER
    print("NetSentinel Capture Engine (Cloud Mode) Started.")
    print(f"Connecting to Laravel API: {LARAVEL_API_URL}")
    print("Monitoring network connections... Press Ctrl+C to stop.")

    while True:
        if not SETTINGS.get('realtime_alerts', True):
            time.sleep(2)
            continue

        connections = read_proc_tcp()
        current_time = time.time()

        for (local_ip, local_port, remote_ip, remote_port, state) in connections:
            # Skip localhost and empty IPs
            if remote_ip in ['0.0.0.0', '127.0.0.1', '::1', '0000:0000:0000:0000']:
                continue

            conn_key = f"{remote_ip}:{local_port}:{state}"
            if conn_key in KNOWN_CONNECTIONS:
                continue
            KNOWN_CONNECTIONS.add(conn_key)

            # Log as packet
            try:
                requests.post(f"{LARAVEL_API_URL}/packets", json={
                    'src_ip': remote_ip,
                    'dst_ip': local_ip,
                    'protocol': 'TCP',
                    'src_mac': 'N/A',
                    'dst_mac': 'N/A',
                    'length': 64
                }, timeout=1)
            except:
                pass

            # Watchlist check
            if remote_ip in WATCHLIST_IPS:
                send_threat("Watchlist Violation", "Critical", remote_ip, local_ip,
                            f"Traffic intercepted from flagged IP {remote_ip}")
                continue

            # Unusual port check
            if local_port in [6667, 31337, 4444, 1337, 9999]:
                send_threat("Unusual Port", "High", remote_ip, local_ip,
                            f"Traffic on unauthorized port {local_port}")
                auto_ban(remote_ip, f"Unauthorized access to port {local_port}")
                continue

            # Port scan detection
            if SETTINGS.get('port_scan', True):
                if remote_ip not in PORT_SCAN_TRACKER:
                    PORT_SCAN_TRACKER[remote_ip] = {'ports': set(), 'start': current_time}

                tracker = PORT_SCAN_TRACKER[remote_ip]
                tracker['ports'].add(local_port)

                if current_time - tracker['start'] > TIME_WINDOW:
                    PORT_SCAN_TRACKER[remote_ip] = {'ports': {local_port}, 'start': current_time}
                elif len(tracker['ports']) >= PORT_SCAN_THRESHOLD:
                    send_threat("Port Scan", "Critical", remote_ip, local_ip,
                                f"Scanned {len(tracker['ports'])} ports in {TIME_WINDOW}s")
                    auto_ban(remote_ip, "Detected performing a rapid Port Scan")
                    PORT_SCAN_TRACKER[remote_ip] = {'ports': set(), 'start': current_time}

        # Limit known connections memory
        if len(KNOWN_CONNECTIONS) > 10000:
            KNOWN_CONNECTIONS.clear()

        time.sleep(1)

if __name__ == '__main__':
    threading.Thread(target=sync_settings, daemon=True).start()
    try:
        analyze_connections()
    except KeyboardInterrupt:
        print("\nNetSentinel Engine stopped.")
