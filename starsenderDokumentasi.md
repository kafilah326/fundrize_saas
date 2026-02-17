KIRIM PESAN API – STARSENDER V3

Standarisasi Pengiriman Pesan menggunakan API pada platform STARSENDER.

============================================================
ENDPOINT

METHOD:
POST

URL:
https://api.starsender.online/api/send

============================================================
HEADERS

Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

Ganti YOUR_API_KEY dengan yang didapat dari device

============================================================
REQUEST BODY (CONTOH)

{
"phone": "6281234567890",
"message": "Halo, ini pesan dari API Starsender!",
"device": "device_id_anda"
}

============================================================
PARAMETER

phone
Tipe : string
Wajib : Ya
Keterangan : Nomor WhatsApp tujuan dalam format internasional
Contoh: 6281234567890 (tanpa tanda +)

message
Tipe : string
Wajib : Ya
Keterangan : Isi pesan teks yang akan dikirim

device
Tipe : string
Wajib : Ya
Keterangan : ID perangkat yang terdaftar di Starsender

============================================================
RESPONSE BERHASIL

{
"status": "success",
"message_id": "1234567890abcdef",
"detail": "Message queued for sending"
}

============================================================
RESPONSE GAGAL

{
"status": "error",
"error": "Invalid phone number or authentication failed"
}

============================================================

CEK NOMOR API – STARSENDER V3

Standarisasi Validasi Nomor WhatsApp menggunakan API pada platform STARSENDER.

============================================================
ENDPOINT

METHOD:
GET / POST (tergantung dokumentasi sebenarnya)

URL:
https://api.starsender.online/api/check-number

============================================================
HEADERS

Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

Ganti YOUR_API_KEY dengan yang didapat dari device

============================================================
REQUEST PARAMETER (CONTOH)

phone
Tipe : string
Wajib : Ya
Keterangan : Nomor WhatsApp yang ingin dicek validitasnya,
dalam format internasional (misal: 6281234567890)

============================================================
REQUEST BODY (CONTOH JSON jika metode POST)
{
  "phone": "6281234567890"
}

============================================================
RESPONSE BERHASIL
{
  "status": "success",
  "phone": "6281234567890",
  "valid": true,
  "registered": true
}


Penjelasan:

status: "success" → permintaan berhasil diproses

phone: nomor yang dicek

valid: apakah format nomor valid

registered: apakah nomor terdaftar di WhatsApp

============================================================
RESPONSE GAGAL / TIDAK TERDAFTAR
{
  "status": "error",
  "message": "Nomor tidak valid atau tidak terdaftar"
}

============================================================

📌 1) DETAIL DEVICE API

DETAIL DEVICE API – STARSENDER V3

Endpoint untuk melihat detail device yang terdaftar.

============================================================
ENDPOINT

METHOD:
GET

URL:
https://api.starsender.online/api/device/detail

============================================================
HEADERS

Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

Gunakan YOUR_API_KEY yang ada .env kalau belum ada buatkan Variablenya

============================================================
QUERY PARAMETER

device_id
Tipe : string
Wajib : Ya
Keterangan : ID device yang ingin diambil detailnya

============================================================
RESPONSE (BERHASIL)

{
"status": "success",
"data": {
"id": "device_id_1234",
"name": "Nama Device",
"status": "connected",
"platform": "android",
"phone": "6281234567890",
"last_seen": "2025-12-01T10:00:00Z"
}
}

============================================================
RESPONSE (GAGAL)

{
"status": "error",
"message": "Device tidak ditemukan atau API key tidak valid"
}

============================================================


📌 2) RELOG DEVICE API

RELOG DEVICE API – STARSENDER V3

Endpoint untuk melakukan re-login ulang device pada Starsender.

============================================================
ENDPOINT

METHOD:
POST

URL:
https://api.starsender.online/api/device/relog

============================================================
HEADERS

Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

============================================================
REQUEST BODY

{
"device_id": "device_id_1234"
}

============================================================
REQUEST PARAMETER

device_id
Tipe : string
Wajib : Ya
Keterangan : ID device yang ingin di-relod

============================================================
RESPONSE (BERHASIL)

{
"status": "success",
"message": "Permintaan relog device berhasil",
"data": {
"device_id": "device_id_1234",
"status": "pending_relogin"
}
}

============================================================
RESPONSE (GAGAL)

{
"status": "error",
"message": "Device tidak ditemukan atau sudah connected"
}

============================================================
CATATAN

Pastikan device dalam keadaan siap untuk relog.

Relog akan menghasilkan status pending sampai QR discan ulang.

📌 3) CREATE & SCAN DEVICE API

CREATE DAN SCAN DEVICE API – STARSENDER V3

Endpoint untuk membuat device baru dan memulai scan QR untuk login.

============================================================
ENDPOINT

METHOD:
POST

URL:
https://api.starsender.online/api/device/create

============================================================
HEADERS

Content-Type: application/json
Authorization: Bearer YOUR_API_KEY

============================================================
REQUEST BODY

{
"name": "Nama Device Anda",
"platform": "android"
}

============================================================
REQUEST PARAMETER

name
Tipe : string
Wajib : Ya
Keterangan : Nama device unik yang akan dibuat

platform
Tipe : string
Wajib : Ya
Keterangan : Platform device (android / ios)

============================================================
RESPONSE (BERHASIL)

{
"status": "success",
"data": {
"device_id": "device_id_baru_1234",
"qr_code": "Base64QRCodeStringHere",
"qr_url": "https://api.starsender.online/qrcode/device_id_baru_1234
"
}
}

============================================================
RESPONSE (GAGAL)

{
"status": "error",
"message": "Nama sudah digunakan atau parameter tidak lengkap"
}

============================================================
CATATAN

Simpan device_id hasil response untuk operasi selanjutnya.

Gunakan qr_code atau qr_url untuk scan login WhatsApp.
