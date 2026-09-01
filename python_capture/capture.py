import sys
import time
import requests
from scapy.all import sniff, IP, TCP, UDP, ICMP, ARP

LARAVEL_API_URL = "http://127.0.0.1:8000/api"

# Configuration
PORT_SCAN_THRESHOLD = 15   # Alert if one IP hits >15 ports
TIME_WINDOW = 5            # In seconds

# State trackers
recent_connections = {}    # { source_ip: { timestamp: [ports] } }

def send_packet_log(src_ip, dst_ip, protocol, src_mac, dst_mac, length):
    try:
        data = {
            "source_ip": src_ip,
            "dest_ip": dst_ip,
            "protocol": protocol,
            "source_mac": src_mac,
            "dest_mac": dst_mac,
            "length": length
        }
        requests.post(f"{LARAVEL_API_URL}/packets", json=data)
    except Exception as e:
        pass

def send_threat_alert(attack_type, severity, attacker_ip, victim_ip, mac, desc):
    print(f"[!] THREAT DETECTED: {attack_type} from {attacker_ip}")
    try:
        data = {
            "attack_type": attack_type,
            "severity": severity,
            "attacker_ip": attacker_ip,
            "victim_ip": victim_ip,
            "attacker_mac": mac,
            "description": desc,
            "status": "Active"
        }
        requests.post(f"{LARAVEL_API_URL}/threats", json=data)
    except Exception as e:
        pass

def detect_port_scan(src_ip, dst_port, current_time):
    # Clean up old records
    if src_ip not in recent_connections:
        recent_connections[src_ip] = []
    
    # Add new port and time
    recent_connections[src_ip].append({'time': current_time, 'port': dst_port})
    
    # Filter out old ones
    recent_connections[src_ip] = [x for x in recent_connections[src_ip] if current_time - x['time'] <= TIME_WINDOW]
    
    # Count unique ports
    unique_ports = set([x['port'] for x in recent_connections[src_ip]])
    
    if len(unique_ports) >= PORT_SCAN_THRESHOLD:
        # Clear the record so we don't spam alerts every millisecond
        recent_connections[src_ip] = []
        return len(unique_ports)
    
    return 0

def process_packet(packet):
    current_time = time.time()
    
    # We only care about IP packets for this basic script
    if IP in packet:
        src_ip = packet[IP].src
        dst_ip = packet[IP].dst
        protocol = "OTHER"
        dst_port = None
        
        src_mac = packet.src if hasattr(packet, 'src') else "Unknown"
        dst_mac = packet.dst if hasattr(packet, 'dst') else "Unknown"
        length = len(packet)
        
        if TCP in packet:
            protocol = "TCP"
            dst_port = packet[TCP].dport
        elif UDP in packet:
            protocol = "UDP"
            dst_port = packet[UDP].dport
        elif ICMP in packet:
            protocol = "ICMP"
            
        # 1. Send packet log to Laravel (We will log 1 out of every 10 packets to prevent crashing your server)
        import random
        if random.random() < 0.1:
            send_packet_log(src_ip, dst_ip, protocol, src_mac, dst_mac, length)
            print(f"[+] Logged Packet: {src_ip} -> {dst_ip}")
        
        # 2. Threat Detection Rules
        if dst_port:
            # Alert on normal web traffic (Port 80 HTTP or 443 HTTPS) to generate screenshots
            if dst_port in [80, 443] and random.random() < 0.05:
                send_threat_alert("Suspicious Web Traffic", "Medium", src_ip, dst_ip, src_mac, f"Unverified outbound connection to port {dst_port}")

            # Rule A: Unusual Ports (e.g. 6667 IRC often used by old botnets)
            if dst_port in [6667, 31337]:
                send_threat_alert("Unusual Port", "High", src_ip, dst_ip, src_mac, f"Traffic on unauthorized port {dst_port}")
                
            # Rule B: Port Scanning Detection
            scanned_count = detect_port_scan(src_ip, dst_port, current_time)
            if scanned_count > 0:
                send_threat_alert("Port Scan", "Critical", src_ip, dst_ip, src_mac, f"Scanned {scanned_count} ports in {TIME_WINDOW}s")

print("NetSentinel Capture Engine Started.")
print("Sniffing network traffic... Press Ctrl+C to stop.")
# Adjust 'iface' to your specific network interface if needed (e.g. iface="eth0")
try:
    sniff(prn=process_packet, store=False)
except KeyboardInterrupt:
    print("\nCapture stopped.")
    sys.exit(0)
