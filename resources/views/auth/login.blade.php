@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 900px; width: 100%;">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1000') center/cover;">
            </div>
            
            <div class="col-md-6 p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="font-family: 'Playfair Display', serif;">Selamat Datang Kembali</h3>
                    <p class="text-muted small text-center">Masuk untuk mengelola pesanan marmer Anda.</p>
                </div>

                {{-- Tampilkan error login jika ada --}}
                @if($errors->any())
                    <div class="alert alert-danger py-2 small border-0 rounded-0">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control py-2 border-dark" placeholder="nama@email.com" required style="border-radius: 0;" value="{{ old('email') }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control py-2 border-dark" placeholder="********" required style="border-radius: 0;">
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2 fw-bold" style="border-radius: 0;">LOGIN</button>
                </form>

                <div class="text-center my-3">
                    <span class="text-muted small">ATAU</span>
                </div>

                <a href="{{ route('google.login') }}" class="btn btn-outline-dark w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 0;">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gmail_512dp.png" width="20" class="me-2">
                    Masuk dengan Google
                </a>

                <div class="text-center mt-4">
                    <p class="small text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-dark fw-bold">Daftar Sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection