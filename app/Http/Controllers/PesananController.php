<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('pesanan.index', compact('pesanan'));
    }

    public function create(Request $request)
    {
        $produkId = $request->query('produk_id');
        $produkTerpilih = null;
        $produkData = null;

        if ($produkId) {
            $p = Produk::find($produkId);
            if ($p) {
                $produkData = $p;
                $produkTerpilih = $p->nama_produk;
            }
        }

        $produk = Produk::all();
        $listBahan = Bahan::all();
        $listTerminal = Terminal::all();

        return view('pesanan.create', compact('produk', 'produkTerpilih', 'produkData', 'listBahan', 'listTerminal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'ukuran' => 'required',
            'jenis_marmer' => 'required',
            'jumlah' => 'required|integer|min:1',
            'metode_pengambilan' => 'required',
            'terminal_id' => 'required_if:metode_pengambilan,dikirim',
            'alamat_manual' => 'required_if:terminal_id,lainnya',
            'gambar_referensi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'total_harga' => 'required',
            'biaya_pengiriman' => 'required',
        ]);

        $alamatFinal = 'Ambil di Tempat (Tulungagung)';
        if ($request->metode_pengambilan == 'dikirim') {
            if ($request->terminal_id === 'lainnya') {
                $alamatFinal = "Kirim ke (Manual): " . $request->alamat_manual;
            } else {
                $terminal = Terminal::find($request->terminal_id);
                if ($terminal) {
                    $alamatFinal = "Kirim via Bus ke: " . $terminal->nama_terminal;
                }
            }
        }

        $pathGambar = null;
        if ($request->hasFile('gambar_referensi')) {
            $pathGambar = $request->file('gambar_referensi')->store('pesanan_custom', 'public');
        }

        Pesanan::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'ukuran' => $request->ukuran,
            'jenis_marmer' => $request->jenis_marmer,
            'catatan_khusus' => $request->catatan_khusus,
            'gambar_referensi' => $pathGambar,
            'jumlah' => $request->jumlah,
            'metode_pengambilan' => $request->metode_pengambilan,
            'alamat_pengiriman' => $alamatFinal,
            'biaya_pengiriman' => $request->biaya_pengiriman,
            'total_harga' => $request->total_harga,
            'status' => 'Menunggu Verifikasi Admin',
            'status_pembayaran' => 'no_paid',
        ]);

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil diajukan!');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($pesanan->gambar_referensi) {
            Storage::disk('public')->delete($pesanan->gambar_referensi);
        }
        $pesanan->delete();
        return redirect()->route('pesanan.index')->with('success', 'Pesanan telah dihapus.');
    }

    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'Selesai']);
        return redirect()->back()->with('success', 'Pesanan selesai!');
    }

    public function getSnapToken($id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'ORD-' . $pesanan->id . '-' . time();

        $totalAkhir = (int) $pesanan->total_harga + (int) ($pesanan->biaya_pengiriman ?? 0);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalAkhir,
            ],
            'customer_details' => [
                'first_name' => $pesanan->user->name,
                'email' => $pesanan->user->email,
            ],
            'item_details' => [
                [
                    'id' => 'PRODUK-' . $pesanan->id,
                    'price' => (int) $pesanan->total_harga,
                    'quantity' => 1,
                    'name' => $pesanan->nama_produk,
                ],
                [
                    'id' => 'ONGKIR-' . $pesanan->id,
                    'price' => (int) ($pesanan->biaya_pengiriman ?? 0),
                    'quantity' => 1,
                    'name' => 'Biaya Titip Bus',
                ],
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $pesanan->update(['midtrans_order_id' => $orderId]);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function midtransCallback(Request $request)
    {
        Log::info('Midtrans callback masuk', $request->all());

        $serverKey = config('midtrans.server_key');
        $payload = $request->all();

        if (empty($payload['order_id']) || empty($payload['signature_key'])) {
            Log::error('Midtrans: payload tidak lengkap', $payload);
            return response()->json(['message' => 'Invalid payload', 'debug' => $payload], 400);
        }

        $signatureKey = hash(
            'sha512',
            $payload['order_id'] .
                $payload['status_code'] .
                $payload['gross_amount'] .
                $serverKey
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::error('Midtrans: signature tidak valid', [
                'expected' => $signatureKey,
                'received' => $payload['signature_key'],
                'order_id' => $payload['order_id'],
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'];

        $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();

        Log::info('Midtrans: hasil cari pesanan', [
            'order_id_dicari' => $orderId,
            'pesanan_ditemukan' => $pesanan ? 'YA - id: ' . $pesanan->id : 'TIDAK',
            'semua_order_id_di_db' => Pesanan::pluck('midtrans_order_id', 'id')->toArray(),
        ]);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Order not found',
                'order_id_dicari' => $orderId,
                'semua_order_id_di_db' => Pesanan::pluck('midtrans_order_id', 'id')->toArray(),
            ], 404);
        }

        $transactionStatus = $payload['transaction_status'];
        $paymentType = $payload['payment_type'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $currency = $payload['currency'] ?? 'IDR';
        $transactionTime = $payload['transaction_time'] ?? null;

        $statusPembayaran = 'no_paid';
        $tanggalBayar = null;

        if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
            $statusPembayaran = 'paid';
            $tanggalBayar = $transactionTime;
        } elseif ($transactionStatus == 'settlement') {
            $statusPembayaran = 'paid';
            $tanggalBayar = $transactionTime;
        }

        $dataUpdate = [
            'midtrans_transaction_id' => $transactionId,
            'midtrans_payment_type'   => $paymentType,
            'midtrans_status'         => $transactionStatus,
            'midtrans_gross_amount'   => $grossAmount,
            'midtrans_currency'       => $currency,
            'midtrans_fraud_status'   => $fraudStatus,
            'status_pembayaran'       => $statusPembayaran,
            'tanggal_bayar'           => $tanggalBayar,
        ];

        Log::info('Midtrans: data yang akan diupdate', $dataUpdate);

        try {
            $result = $pesanan->update($dataUpdate);

            $pesananSesudah = $pesanan->fresh();

            Log::info('Midtrans: hasil update', [
                'update_result' => $result,
                'status_pembayaran_sesudah' => $pesananSesudah->status_pembayaran,
                'midtrans_status_sesudah'   => $pesananSesudah->midtrans_status,
                'fillable_model'            => $pesanan->getFillable(),
            ]);

            return response()->json([
                'message'                  => 'OK',
                'update_result'            => $result,
                'status_pembayaran_sesudah' => $pesananSesudah->status_pembayaran,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans: EXCEPTION saat update', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Exception: ' . $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }
}
