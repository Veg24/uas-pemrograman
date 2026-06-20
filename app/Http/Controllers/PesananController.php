<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        $menus = $query->get();
        return view('pesan.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:dine_in,delivery',
            'metode_pembayaran' => 'required|in:qris,transfer,cash',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'nullable|required_if:tipe,delivery|string|max:255',
            'alamat_penerima' => 'nullable|required_if:tipe,delivery|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'promo_code' => 'nullable|string',
        ]);

        try {
            $pesananId = DB::transaction(function () use ($request) {
                $subtotal = 0;
                $itemsToCreate = [];

                foreach ($request->items as $item) {
                    $menu = Menu::where('id', $item['menu_id'])->lockForUpdate()->firstOrFail();
                    
                    if ($menu->stok < $item['jumlah']) {
                        throw new \Exception('Stok untuk menu "' . $menu->nama_menu . '" tidak mencukupi. Tersisa: ' . $menu->stok);
                    }

                    $itemSubtotal = $menu->harga * $item['jumlah'];
                    $subtotal += $itemSubtotal;

                    $itemsToCreate[] = [
                        'menu_id' => $menu->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $menu->harga,
                        'subtotal' => $itemSubtotal,
                        'menu' => $menu,
                    ];
                }

                // Apply Promo Code (LUMIERE10 = 10% discount)
                $diskon = 0;
                if ($request->filled('promo_code') && strtoupper($request->promo_code) === 'LUMIERE10') {
                    $diskon = $subtotal * 0.10;
                }
                
                $totalHarga = $subtotal - $diskon;

                // Create Pesanan
                $pesanan = Pesanan::create([
                    'user_id' => Auth::id(),
                    'total_harga' => $totalHarga,
                    'status' => 'diproses',
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'tipe' => $request->tipe,
                    'nama_penerima' => $request->tipe === 'delivery' ? $request->nama_penerima : null,
                    'alamat_penerima' => $request->tipe === 'delivery' ? $request->alamat_penerima : null,
                    'catatan' => $request->catatan,
                ]);

                // Create items & update stock
                foreach ($itemsToCreate as $itemData) {
                    PesananItem::create([
                        'pesanan_id' => $pesanan->id,
                        'menu_id' => $itemData['menu_id'],
                        'jumlah' => $itemData['jumlah'],
                        'harga_satuan' => $itemData['harga_satuan'],
                        'subtotal' => $itemData['subtotal'],
                    ]);

                    // Update stock
                    $itemData['menu']->decrement('stok', $itemData['jumlah']);
                }

                // Log to AuditLog
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'aksi' => 'Pemesanan Makanan',
                    'keterangan' => 'Membuat pesanan makanan #' . $pesanan->id . ' dengan total Rp' . number_format($totalHarga, 0, ',', '.'),
                ]);

                return $pesanan->id;
            });

            return redirect()->route('pesan.show', $pesananId)->with('success', 'Pesanan makanan Anda berhasil dikirim ke dapur!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(int $id)
    {
        $pesanan = Pesanan::with('pesananItems.menu')->where('user_id', Auth::id())->findOrFail($id);
        return view('pesan.show', compact('pesanan'));
    }
}
