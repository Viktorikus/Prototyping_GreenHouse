import serial
import time
import requests

def main():
    while True:
        try:
            ser = serial.Serial('/dev/ttyS0', 9600, timeout=2)
            ser.flush()
            print("Koneksi serial berhasil.")
            break
        except serial.SerialException as e:
            print(f"Error koneksi serial: {e}")
            print("Mencoba kembali dalam 5 detik...")
            time.sleep(5)

    while True:
        try:
            if ser.in_waiting > 0:
                line = ser.readline().decode('utf-8').rstrip()
                if line.startswith('[') and line.endswith(']'):
                    data = line[1:-1].split(',')
                    if len(data) == 4:
                        h, t, l, s = data
                        humidity = float(h)
                        temperature = float(t)
                        light_level = float(l)
                       
                        if s == "0":
                            lamp_status = "OFF"
                        else :
                            lamp_status = "ON"
                        print(f"Kelembapan: {humidity} %, Suhu: {temperature} *C, Intensitas Cahaya: {light_level}, Lamp Status: {lamp_status}")

                        url = "http://192.168.113.97/api/add.php"
                        payload = {"temperature":temperature, "humidity":humidity,
                                    "light_level":light_level, "lamp_status":lamp_status}

                        try:
                            response = requests.post(url, data=payload)
                            print(response.text)
                            if response.status_code == 200:
                                print("Data berhasil dikirim.")
                            else:
                                print(f"Terjadi kesalahan saat mengirim data: {response.status_code}")
                        except requests.exceptions.RequestException as e:
                            print(f"Error saat mengirim request: {e}")

                    else:
                        print(f"Data tidak lengkap: {line}")
                else:
                    print(f"Data tidak valid: {line}")
        except serial.SerialException as e:
            print(f"Serial error: {e}")
            print("Mencoba untuk menyambung kembali...")
            ser.close()
            while True:
                try:
                    ser = serial.Serial('/dev/ttyS0', 9600, timeout=2)
                    ser.flush()
                    print("Koneksi serial berhasil kembali.")
                    break
                except serial.SerialException as e:
                    print(f"Rekoneksi gagal: {e}")
                    print("Mencoba kembali dalam 5 detik...")
                    time.sleep(5)
        except Exception as e:
            print(f"Kesalahan: {e}")

if _name_ == "_main_":
    main()