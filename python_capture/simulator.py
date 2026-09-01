import time
import random
import requests

LARAVEL_API_URL = "http://127.0.0.1:8000/api"

print("NetSentinel Traffic Simulator Started.")
print("Generating realistic network traffic and threats...")

def send_packet():
    src_ip = f"192.168.1.{random.randint(10, 200)}"
    dst_ip = f"10.0.0.{random.randint(1, 5)}"
    protocols = ["TCP", "UDP", "ICMP"]
    
    data = {
        "source_ip": src_ip,
        "dest_ip": dst_ip,
        "protocol": random.choice(protocols),
        "source_mac": "00:15:5D:22:BB:CC",
        "dest_mac": "00:50:56:C0:00:01",
        "length": random.randint(60, 1500)
    }
    try:
        requests.post(f"{LARAVEL_API_URL}/packets", json=data)
        print(f"[+] Logged Packet: {src_ip} -> {dst_ip}")
    except:
        print("[-] API offline. Is Laravel running?")

def send_threat():
    attack_types = ["Port Scan", "Flooding", "Suspicious IP", "Unusual Port"]
    attack = random.choice(attack_types)
    src_ip = f"198.51.100.{random.randint(1, 200)}"
    
    severity = "High" if attack in ["Port Scan", "Suspicious IP"] else "Medium"
    
    data = {
        "attack_type": attack,
        "severity": severity,
        "attacker_ip": src_ip,
        "victim_ip": "192.168.1.10",
        "attacker_mac": "3C:97:0E:12:34:56",
        "description": f"Simulated {attack} detected",
        "status": "Active"
    }
    try:
        requests.post(f"{LARAVEL_API_URL}/threats", json=data)
        print(f"[!] THREAT ALERT: {attack} from {src_ip}")
    except:
        pass

try:
    while True:
        # Send 3 normal packets
        for _ in range(3):
            send_packet()
            time.sleep(0.5)
            
        # Randomly send a threat (20% chance)
        if random.random() < 0.2:
            send_threat()
            
        time.sleep(1)
except KeyboardInterrupt:
    print("\nSimulation stopped.")
