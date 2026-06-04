<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->input('tab', 'semua');
        $periode = $request->input('periode', '30_days');

        // Calculate statistics
        $totalReservasi = Reservasi::where('user_id', $user->id)->count();
        $pesananSelesai = Pesanan::where('user_id', $user->id)->where('status', 'selesai')->count();
        
        $reservasiDibatalkan = Reservasi::where('user_id', $user->id)->where('status', 'dibatalkan')->count();
        $pesananDibatalkan = Pesanan::where('user_id', $user->id)->where('status', 'dibatalkan')->count();
        $totalDibatalkan = $reservasiDibatalkan + $pesananDibatalkan;

        // Query database
        $reservasiQuery = Reservasi::with('meja')->where('user_id', $user->id);
        $pesananQuery = Pesanan::where('user_id', $user->id);

        // Filter by period
        if ($periode !== 'semua') {
            $dateLimit = match ($periode) {
                '30_days' => Carbon::now()->subDays(30),
                '6_months' => Carbon::now()->subMonths(6),
                '1_year' => Carbon::now()->subYear(),
                default => Carbon::now()->subDays(30),
            };

            $reservasiQuery->where('created_at', '>=', $dateLimit);
            $pesananQuery->where('created_at', '>=', $dateLimit);
        }

        $activities = collect();

        // Gather reservations if applicable
        if ($tab === 'semua' || $tab === 'reservasi' || $tab === 'dibatalkan') {
            $reservasis = $reservasiQuery->get();
            foreach ($reservasis as $r) {
                if ($tab === 'dibatalkan' && $r->status !== 'dibatalkan') {
                    continue;
                }
                $activities->push((object)[
                    'id' => $r->id,
                    'tipe' => 'reservasi',
                    'judul' => 'Reservasi Meja #' . ($r->meja ? $r->meja->nomor_meja : 'N/A'),
                    'deskripsi' => 'Tanggal: ' . $r->tanggal->format('d M Y') . ' Jam: ' . substr($r->jam, 0, 5) . ' • ' . $r->jumlah_tamu . ' Tamu',
                    'status' => $r->status,
                    'created_at' => $r->created_at,
                    'url' => route('reservasi.show', $r->id),
                ]);
            }
        }

        // Gather orders if applicable
        if ($tab === 'semua' || $tab === 'pesan' || $tab === 'dibatalkan') {
            $pesanans = $pesananQuery->get();
            foreach ($pesanans as $p) {
                if ($tab === 'dibatalkan' && $p->status !== 'dibatalkan') {
                    continue;
                }
                $activities->push((object)[
                    'id' => $p->id,
                    'tipe' => 'pesan',
                    'judul' => 'Pemesanan Makanan #' . $p->id,
                    'deskripsi' => 'Total: Rp' . number_format($p->total_harga, 0, ',', '.') . ' • Tipe: ' . ($p->tipe === 'dine_in' ? 'Dine In' : 'Delivery'),
                    'status' => $p->status,
                    'created_at' => $p->created_at,
                    'url' => route('pesan.show', $p->id),
                ]);
            }
        }

        // Sort unified activities by created_at descending
        $sortedActivities = $activities->sortByDesc('created_at')->values();

        // Paginate manually
        $page = $request->input('page', 1);
        $perPage = 5;
        $paginatedItems = $sortedActivities->slice(($page - 1) * $perPage, $perPage)->all();
        
        $paginatedActivities = new LengthAwarePaginator(
            $paginatedItems,
            $sortedActivities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('riwayat.index', compact(
            'paginatedActivities',
            'totalReservasi',
            'pesananSelesai',
            'totalDibatalkan',
            'tab',
            'periode'
        ));
    }
}
