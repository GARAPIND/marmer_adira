<?php

namespace App\Http\Controllers;

use App\Models\AlamatPembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AlamatPembeliController extends Controller
{
    private function roHeaders(): array
    {
        return [
            'key'    => config('services.rajaongkir.key'),
            'Accept' => 'application/json',
        ];
    }

    public function index()
    {
        $alamat = AlamatPembeli::where('user_id', Auth::id())->latest()->get();
        return view('alamat.index', compact('alamat'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'label'          => 'required|string|max:100',
            'nama_penerima'  => 'required|string|max:255',
            'no_telepon'     => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'provinsi_id'    => 'required',
            'provinsi_nama'  => 'required',
            'kota_id'        => 'required',
            'kota_nama'      => 'required',
            'kecamatan_id'   => 'required',
            'kecamatan_nama' => 'required',
            'kode_pos'       => 'nullable|string|max:10',
        ]);

        if ($request->boolean('is_utama')) {
            AlamatPembeli::where('user_id', Auth::id())->update(['is_utama' => false]);
        }

        $alamat = AlamatPembeli::create([
            'user_id'        => Auth::id(),
            'label'          => $request->label,
            'nama_penerima'  => $request->nama_penerima,
            'no_telepon'     => $request->no_telepon,
            'alamat_lengkap' => $request->alamat_lengkap,
            'provinsi_id'    => $request->provinsi_id,
            'provinsi_nama'  => $request->provinsi_nama,
            'kota_id'        => $request->kota_id,
            'kota_nama'      => $request->kota_nama,
            'kecamatan_id'   => $request->kecamatan_id,
            'kecamatan_nama' => $request->kecamatan_nama,
            'kode_pos'       => $request->kode_pos,
            'is_utama'       => $request->boolean('is_utama'),
        ]);
        // dd($alamat);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'alamat' => $alamat]);
        }

        return redirect()->back()->with('success', 'Alamat berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $alamat = AlamatPembeli::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'label'          => 'required|string|max:100',
            'nama_penerima'  => 'required|string|max:255',
            'no_telepon'     => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'provinsi_id'    => 'required',
            'provinsi_nama'  => 'required',
            'kota_id'        => 'required',
            'kota_nama'      => 'required',
            'kecamatan_id'   => 'required',
            'kecamatan_nama' => 'required',
            'kode_pos'       => 'nullable|string|max:10',
        ]);

        if ($request->boolean('is_utama')) {
            AlamatPembeli::where('user_id', Auth::id())->where('id', '!=', $id)->update(['is_utama' => false]);
        }

        $alamat->update([
            'label'          => $request->label,
            'nama_penerima'  => $request->nama_penerima,
            'no_telepon'     => $request->no_telepon,
            'alamat_lengkap' => $request->alamat_lengkap,
            'provinsi_id'    => $request->provinsi_id,
            'provinsi_nama'  => $request->provinsi_nama,
            'kota_id'        => $request->kota_id,
            'kota_nama'      => $request->kota_nama,
            'kecamatan_id'   => $request->kecamatan_id,
            'kecamatan_nama' => $request->kecamatan_nama,
            'kode_pos'       => $request->kode_pos,
            'is_utama'       => $request->boolean('is_utama'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'alamat' => $alamat->fresh()]);
        }

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alamat = AlamatPembeli::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $alamat->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Alamat dihapus.');
    }

    public function setUtama($id)
    {
        AlamatPembeli::where('user_id', Auth::id())->update(['is_utama' => false]);
        AlamatPembeli::where('id', $id)->where('user_id', Auth::id())->update(['is_utama' => true]);
        return response()->json(['success' => true]);
    }

    public function getList()
    {
        $alamat = AlamatPembeli::where('user_id', Auth::id())->latest()->get();
        return response()->json($alamat);
    }

    public function getProvinsi()
    {
        $res = Http::withHeaders($this->roHeaders())
            ->get('https://rajaongkir.komerce.id/api/v1/destination/province');
        // dd($res->json());
        return response()->json($res->json());
    }

    public function getKota(Request $request)
    {
        $request->validate(['province_id' => 'required']);

        $res = Http::withHeaders($this->roHeaders())
            ->get('https://rajaongkir.komerce.id/api/v1/destination/city/' . $request->province_id);
        // dd($res->json());
        return response()->json($res->json());
    }

    public function getKecamatan(Request $request)
    {
        $request->validate(['city_id' => 'required']);

        $res = Http::withHeaders($this->roHeaders())
            ->get('https://rajaongkir.komerce.id/api/v1/destination/district/' . $request->city_id);
        // dd($res->json());
        return response()->json($res->json());
    }

    public function hitungOngkir(Request $request)
    {
        $request->validate([
            'origin'      => 'required',
            'destination' => 'required',
            'weight'      => 'required|integer|min:1',
            'courier'     => 'required',
        ]);

        $res = Http::withHeaders($this->roHeaders())
            ->asForm()
            ->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
                'origin'      => $request->origin,
                'destination' => $request->destination,
                'weight'      => $request->weight,
                'courier'     => $request->courier,
                'price'       => 'lowest',
            ]);
        // dd($res->json());
        return response()->json($res->json());
    }
}
