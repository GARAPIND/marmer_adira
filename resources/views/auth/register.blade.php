@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 80px;">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 500px; width: 100%;">
        <div class="p-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1a1a1a;">Registrasi Pengguna Baru</h2>
                <p class="text-muted small text-center px-4">Gunakan data yang valid untuk proses pemesanan marmer Anda.</p>
            </div>

            <form action="{{ route('register.proses') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="name" class="form-control py-2 border-dark @error('name') is-invalid @enderror" placeholder="Nama Lengkap" required value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <input type="email" name="email" class="form-control py-2 border-dark @error('email') is-invalid @enderror" placeholder="Email" required value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    {{-- PERBAIKAN: name diubah dari no_telepon menjadi no_telp sesuai database --}}
                    <input type="text" name="no_telp" class="form-control py-2 border-dark @error('no_telp') is-invalid @enderror" placeholder="Nomor Telepon" required value="{{ old('no_telp') }}">
                    @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control py-2 border-dark @error('password') is-invalid @enderror" placeholder="Password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <input type="password" name="password_confirmation" class="form-control py-2 border-dark" placeholder="Konfirmasi Password" required>
                </div>

                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold" style="border-radius: 0;">Daftar Sekarang</button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-dark fw-bold">Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection