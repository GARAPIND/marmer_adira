<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Terminal;
use App\Models\AlamatPembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    private function updatePaymentStatus(Pesanan $pesanan, array $payload): string
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $statusPembayaran  = 'no_paid';
        $tanggalBayar      = null;

        if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {
            $statusPembayaran = 'paid';
            $tanggalBayar     = $payload['transaction_time'] ?? now();
        }

        $pesanan->update([
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $pesanan->midtrans_transaction_id,
            'midtrans_payment_type'   => $payload['payment_type'] ?? $pesanan->midtrans_payment_type,
            'midtrans_status'         => $transactionStatus,
            'midtrans_gross_amount'   => $payload['gross_amount'] ?? $pesanan->midtrans_gross_amount,
            'midtrans_currency'       => $payload['currency'] ?? $pesanan->midtrans_currency ?? 'IDR',
            'midtrans_fraud_status'   => $fraudStatus,
            'status_pembayaran'       => $statusPembayaran,
            'tanggal_bayar'           => $tanggalBayar,
        ]);

        return $statusPembayaran;
    }

    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('pesanan.index', compact('pesanan'));
    }

    public function create(Request $request)
    {
        // dd(1);
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

        $produk      = Produk::all();
        $listBahan   = Bahan::all();
        $listTerminal = Terminal::all();
        $listAlamat  = AlamatPembeli::where('user_id', Auth::id())->latest()->get();
        // dd($listAlamat);
        return view('pesanan.create', compact('produk', 'produkTerpilih', 'produkData', 'listBahan', 'listTerminal', 'listAlamat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'        => 'required|string|max:255',
            'ukuran'             => 'required',
            'jenis_marmer'       => 'required',
            'jumlah'             => 'required|integer|min:1',
            'metode_pengambilan' => 'required',
            'jenis_pengiriman'   => 'required_if:metode_pengambilan,dikirim',
            'terminal_id'        => 'required_if:jenis_pengiriman,bus',
            'alamat_manual'      => 'required_if:terminal_id,lainnya',
            'alamat_pembeli_id'  => 'required_if:jenis_pengiriman,cargo',
            'courier'            => 'required_if:jenis_pengiriman,cargo',
            'gambar_referensi'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'total_harga'        => 'required',
            'biaya_pengiriman'   => 'required',
        ]);

        $alamatFinal = 'Ambil di Tempat (Tulungagung)';
        $alamatPembeliId = null;
        $jenisPengiriman = null;

        if ($request->metode_pengambilan === 'dikirim') {
            $jenisPengiriman = $request->jenis_pengiriman;

            if ($jenisPengiriman === 'bus') {
                if ($request->terminal_id === 'lainnya') {
                    $alamatFinal = 'Kirim ke (Manual): ' . $request->alamat_manual;
                } else {
                    $terminal = Terminal::find($request->terminal_id);
                    if ($terminal) {
                        $alamatFinal = 'Kirim via Bus ke: ' . $terminal->nama_terminal;
                    }
                }
            } elseif ($jenisPengiriman === 'cargo') {
                $alamat = AlamatPembeli::find($request->alamat_pembeli_id);
                if ($alamat) {
                    $alamatFinal    = $alamat->alamat_lengkap . ', ' . $alamat->kecamatan_nama . ', ' . $alamat->kota_nama . ', ' . $alamat->provinsi_nama;
                    $alamatPembeliId = $alamat->id;
                }
            }
        }

        $pathGambar = null;
        if ($request->hasFile('gambar_referensi')) {
            $pathGambar = $request->file('gambar_referensi')->store('pesanan_custom', 'public');
        }

        Pesanan::create([
            'user_id'            => Auth::id(),
            'nama_produk'        => $request->nama_produk,
            'ukuran'             => $request->ukuran,
            'jenis_marmer'       => $request->jenis_marmer,
            'catatan_khusus'     => $request->catatan_khusus,
            'gambar_referensi'   => $pathGambar,
            'jumlah'             => $request->jumlah,
            'metode_pengambilan' => $request->metode_pengambilan,
            'jenis_pengiriman'   => $jenisPengiriman,
            'alamat_pembeli_id'  => $alamatPembeliId,
            'alamat_pengiriman'  => $alamatFinal,
            'biaya_pengiriman'   => $request->biaya_pengiriman,
            'total_harga'        => $request->total_harga,
            'status'             => 'Menunggu Verifikasi Admin',
            'status_pembayaran'  => 'no_paid',
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

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $orderId    = 'ORD-' . $pesanan->id . '-' . time();
        $totalAkhir = (int) $pesanan->total_harga + (int) ($pesanan->biaya_pengiriman ?? 0);
        $itemDetails = [
            [
                'id'       => 'PRODUK-' . $pesanan->id,
                'price'    => (int) $pesanan->total_harga,
                'quantity' => 1,
                'name'     => $pesanan->nama_produk,
            ],
        ];

        if ((int) ($pesanan->biaya_pengiriman ?? 0) > 0) {
            $itemDetails[] = [
                'id'       => 'ONGKIR-' . $pesanan->id,
                'price'    => (int) $pesanan->biaya_pengiriman,
                'quantity' => 1,
                'name'     => 'Biaya Pengiriman',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalAkhir,
            ],
            'customer_details' => [
                'first_name' => $pesanan->user->name,
                'email'      => $pesanan->user->email,
            ],
            'item_details' => $itemDetails,
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $pesanan->update(['midtrans_order_id' => $orderId]);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function paymentSuccess(Request $request, $id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'order_id'           => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_id'     => 'nullable|string',
            'payment_type'       => 'nullable|string',
            'gross_amount'       => 'nullable',
            'currency'           => 'nullable|string',
            'fraud_status'       => 'nullable|string',
            'transaction_time'   => 'nullable',
        ]);

        if ($pesanan->midtrans_order_id !== $request->order_id) {
            return response()->json(['message' => 'Order ID tidak cocok'], 422);
        }

        $statusPembayaran = $this->updatePaymentStatus($pesanan, $request->all());

        return response()->json([
            'message' => 'Pembayaran berhasil disinkronkan',
            'status_pembayaran' => $statusPembayaran,
        ]);
    }

    public function midtransCallback(Request $request)
    {
        Log::info('Midtrans callback masuk', $request->all());

        $serverKey = config('midtrans.server_key');
        $payload   = $request->all();

        if (empty($payload['order_id']) || empty($payload['signature_key'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $signatureKey = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey
        );

        if ($signatureKey !== $payload['signature_key']) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('midtrans_order_id', $payload['order_id'])->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        try {
            $statusPembayaran = $this->updatePaymentStatus($pesanan, $payload);

            return response()->json(['message' => 'OK', 'status_pembayaran' => $statusPembayaran]);
        } catch (\Exception $e) {
            Log::error('Midtrans exception', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
}
