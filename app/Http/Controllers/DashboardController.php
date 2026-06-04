<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pesanan;
use App\Models\Menu;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Upcoming Reservasi
        $upcomingReservasi = Reservasi::with('meja')
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->first();

        // Calculate countdown
        $countdown = null;
        if ($upcomingReservasi) {
            $reservationDate = Carbon::parse($upcomingReservasi->tanggal);
            $today = Carbon::today();
            if ($reservationDate->isToday()) {
                $countdown = 'Hari ini';
            } elseif ($reservationDate->isTomorrow()) {
                $countdown = 'Besok';
            } else {
                $diff = $today->diffInDays($reservationDate);
                $countdown = $diff . ' hari lagi';
            }
        }

        // 2. Statistics
        $totalReservasi = Reservasi::where('user_id', $user->id)->count();
        $pesananAktif = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['diproses', 'dikirim'])
            ->count();
        $pesananSelesai = Pesanan::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        // 3. Menu Populer (3 items)
        $menuPopuler = Menu::where('is_populer', true)
            ->take(3)
            ->get();

        if ($menuPopuler->count() < 3) {
            $menuPopuler = Menu::orderBy('harga', 'desc')->take(3)->get();
        }

        // 4. Riwayat Singkat Aktivitas (4 latest audit logs)
        $riwayatSingkat = AuditLog::where('user_id', $user->id)
            ->orderBy('timestamp', 'desc')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'upcomingReservasi',
            'countdown',
            'totalReservasi',
            'pesananAktif',
            'pesananSelesai',
            'menuPopuler',
            'riwayatSingkat'
        ));
    }
}
