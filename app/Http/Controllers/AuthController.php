<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    public function showLoginForm() 
    { 
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function showRegisterForm() 
    { 
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => 'required|string|max:15', 
            'password' => 'required|string|min:8|confirmed',
        ]);

        // PERBAIKAN: Password WAJIB di-hash agar bisa dibaca oleh Auth::attempt
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password), 
            'role' => 'pembeli', 
        ]);

        Auth::login($user);
        return $this->redirectBasedOnRole($user);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $userGoogle = Socialite::driver('google')->user();
            $user = User::where('email', $userGoogle->getEmail())->first();

            if ($user) {
                $user->update(['google_id' => $userGoogle->getId()]);
                Auth::login($user, true); 
            } else {
                $user = User::create([
                    'name' => $userGoogle->getName(),
                    'email' => $userGoogle->getEmail(),
                    'google_id' => $userGoogle->getId(),
                    'role' => 'pembeli',
                    'no_telp' => null, 
                    'password' => null, 
                ]);
                Auth::login($user, true);
            }
            return $this->redirectBasedOnRole($user);
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login Google: ' . $e->getMessage()]);
        }
    }

    /**
     * PERBAIKAN: Logika pengalihan berdasarkan role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard'); 
        }
        
        // PERBAIKAN: Arahkan pengrajin ke dashboard mereka, bukan ke rute pembeli
        if ($user->role == 'pengrajin') {
            return redirect()->route('pengrajin.dashboard');
        }

        // Redirect ke dashboard pembeli
        return redirect()->route('pembeli.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil keluar.');
    }
}