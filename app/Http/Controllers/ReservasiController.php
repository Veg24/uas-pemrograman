<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Meja;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    public function index()
    {
        $mejas = Meja::all();
        $reservasis = Reservasi::where('status', '!=', 'dibatalkan')
            ->where('tanggal', '>=', today()->toDateString())
            ->get(['meja_id', 'tanggal', 'jam'])
            ->map(function ($r) {
                return [
                    'meja_id' => $r->meja_id,
                    'tanggal' => $r->tanggal->format('Y-m-d'),
                    'jam' => substr($r->jam, 0, 5),
                ];
            });

        return view('reservasi.index', compact('mejas', 'reservasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'meja_id' => 'required|exists:mejas,id',
            'jumlah_tamu' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $meja = Meja::findOrFail($request->meja_id);
                
                // 1. Check if table is under maintenance
                if ($meja->status === 'maintenance') {
                    throw new \Exception('Meja #' . $meja->nomor_meja . ' saat ini sedang tidak tersedia karena pemeliharaan.');
                }

                // 2. Validate table capacity
                if ($request->jumlah_tamu > $meja->kapasitas) {
                    throw new \Exception('Jumlah tamu (' . $request->jumlah_tamu . ' orang) melebihi kapasitas maksimal Meja #' . $meja->nomor_meja . ' (Maksimal: ' . $meja->kapasitas . ' orang).');
                }

                // 3. Check for reservation date/time conflicts (overlapping reservations)
                $conflict = Reservasi::where('meja_id', $request->meja_id)
                    ->where('tanggal', $request->tanggal)
                    ->where('jam', $request->jam)
                    ->where('status', '!=', 'dibatalkan')
                    ->exists();

                if ($conflict) {
                    throw new \Exception('Meja #' . $meja->nomor_meja . ' sudah dipesan untuk tanggal ' . $request->tanggal . ' jam ' . $request->jam . '.');
                }

                // Create reservation
                Reservasi::create([
                    'user_id' => Auth::id(),
                    'meja_id' => $request->meja_id,
                    'tanggal' => $request->tanggal,
                    'jam' => $request->jam,
                    'jumlah_tamu' => $request->jumlah_tamu,
                    'catatan' => $request->catatan,
                    'status' => 'dikonfirmasi', // Default status as confirmed
                    'biaya_deposit' => 50000.00,
                ]);

                // Log to AuditLog
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'aksi' => 'Reservasi Meja',
                    'keterangan' => 'Membuat reservasi Meja #' . $meja->nomor_meja . ' (' . ucfirst($meja->area) . ') untuk tanggal ' . $request->tanggal . ' jam ' . $request->jam,
                ]);
            });

            return redirect()->route('dashboard')->with('success', 'Reservasi meja Anda berhasil dikonfirmasi!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat reservasi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(int $id)
    {
        $reservasi = Reservasi::with('meja')->where('user_id', Auth::id())->findOrFail($id);
        return view('reservasi.show', compact('reservasi'));
    }

    public function destroy(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $reservasi = Reservasi::where('user_id', Auth::id())->findOrFail($id);
                
                $meja = $reservasi->meja;

                // Update reservation status to dibatalkan
                $reservasi->update(['status' => 'dibatalkan']);

                // Log to AuditLog
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'aksi' => 'Pembatalan Reservasi',
                    'keterangan' => 'Membatalkan reservasi Meja #' . ($meja ? $meja->nomor_meja : 'N/A') . ' tanggal ' . $reservasi->tanggal->format('Y-m-d'),
                ]);
            });

            return redirect()->route('riwayat')->with('success', 'Reservasi berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan reservasi: ' . $e->getMessage());
        }
    }
}
