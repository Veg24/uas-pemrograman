<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // 1. Build unified queries
        $reservasiSub = DB::table('reservasis')
            ->select('id', DB::raw("'reservasi' as tipe"), 'status', 'created_at')
            ->where('user_id', $user->id);

        $pesananSub = DB::table('pesanans')
            ->select('id', DB::raw("'pesan' as tipe"), 'status', 'created_at')
            ->where('user_id', $user->id);

        // Filter by period
        if ($periode !== 'semua') {
            $dateLimit = match ($periode) {
                '30_days' => Carbon::now()->subDays(30),
                '6_months' => Carbon::now()->subMonths(6),
                '1_year' => Carbon::now()->subYear(),
                default => Carbon::now()->subDays(30),
            };

            $reservasiSub->where('created_at', '>=', $dateLimit);
            $pesananSub->where('created_at', '>=', $dateLimit);
        }

        // Apply Tab Filter
        if ($tab === 'reservasi') {
            $unionQuery = $reservasiSub;
        } elseif ($tab === 'pesan') {
            $unionQuery = $pesananSub;
        } else {
            if ($tab === 'dibatalkan') {
                $reservasiSub->where('status', 'dibatalkan');
                $pesananSub->where('status', 'dibatalkan');
            }
            $unionQuery = $reservasiSub->union($pesananSub);
        }

        // Get Paginated IDs and Types
        $paginatedUnion = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Gather details for the 5 items on the current page
        $items = $paginatedUnion->items();
        
        $reservasiIds = [];
        $pesananIds = [];
        foreach ($items as $item) {
            if ($item->tipe === 'reservasi') {
                $reservasiIds[] = $item->id;
            } else {
                $pesananIds[] = $item->id;
            }
        }

        $reservasis = Reservasi::with('meja')->whereIn('id', $reservasiIds)->get()->keyBy('id');
        $pesanans = Pesanan::whereIn('id', $pesananIds)->get()->keyBy('id');

        $activities = collect();
        foreach ($items as $item) {
            if ($item->tipe === 'reservasi') {
                $r = $reservasis->get($item->id);
                if ($r) {
                    $activities->push((object)[
                        'id' => $r->id,
                        'tipe' => 'reservasi',
                        'judul' => 'Reservasi Meja #' . ($r->meja ? $r->meja->nomor_meja : 'N/A'),
                        'deskripsi' => 'Tanggal: ' . $r->tanggal->format('d M Y') . ' Jam: ' . substr($r->jam, 0, 5) . ' • ' . $r->jumlah_tamu . ' Tamu',
                        'status' => $r->status,
                        'created_at' => $r->created_at,
                        'url' => route('reservasi.show', $r->id),
                        'meja_nomor' => $r->meja ? $r->meja->nomor_meja : 'N/A',
                        'area' => $r->meja ? $r->meja->area : '',
                        'jumlah_tamu' => $r->jumlah_tamu,
                        'tanggal' => $r->tanggal,
                        'jam' => $r->jam,
                    ]);
                }
            } else {
                $p = $pesanans->get($item->id);
                if ($p) {
                    $activities->push((object)[
                        'id' => $p->id,
                        'tipe' => 'pesan',
                        'judul' => 'Pemesanan Makanan #' . $p->id,
                        'deskripsi' => 'Total: Rp' . number_format($p->total_harga, 0, ',', '.') . ' • Tipe: ' . ($p->tipe === 'dine_in' ? 'Dine In' : 'Delivery'),
                        'status' => $p->status,
                        'created_at' => $p->created_at,
                        'url' => route('pesan.show', $p->id),
                        'total_harga' => $p->total_harga,
                        'tipe_pesanan' => $p->tipe,
                    ]);
                }
            }
        }

        // Build the final paginator that Blade expects
        $paginatedActivities = new LengthAwarePaginator(
            $activities,
            $paginatedUnion->total(),
            $paginatedUnion->perPage(),
            $paginatedUnion->currentPage(),
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
