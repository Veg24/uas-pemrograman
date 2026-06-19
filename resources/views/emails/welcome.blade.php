<x-mail::message>
# Selamat Datang di Lumière Dining!

Halo **{{ $user->nama }}**,

Terima kasih telah mendaftar di **Lumière Dining**. Akun Anda telah berhasil dibuat dengan rincian berikut:
- **Nama Lengkap:** {{ $user->nama }}
- **Email:** {{ $user->email }}
- **No. Handphone:** {{ $user->no_hp }}

Kini Anda dapat dengan mudah melakukan reservasi meja eksklusif dan memesan menu hidangan premium kami secara online melalui dashboard.

<x-mail::button :url="route('dashboard')">
Masuk ke Dashboard
</x-mail::button>

Jika Anda membutuhkan bantuan, jangan ragu untuk menghubungi layanan pelanggan kami.

Salam hangat,<br>
**Tim Lumière Dining**
</x-mail::message>
