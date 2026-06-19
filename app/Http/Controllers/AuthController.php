<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';

        if (Auth::attempt([$field => $login, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log to AuditLog
            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'Login',
                'keterangan' => 'User berhasil login ke sistem',
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali di Lumière Dining!');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_hp' => $request->no_hp,
                    'password' => Hash::make($request->password),
                ]);

                // Log to AuditLog
                AuditLog::create([
                    'user_id' => $user->id,
                    'aksi' => 'Registrasi',
                    'keterangan' => 'User baru berhasil mendaftar',
                ]);

                return $user;
            });

            Auth::login($user);

            try {
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Exception $e) {
                logger()->warning('Gagal mengirim email sambutan ke ' . $user->email . ': ' . $e->getMessage());
            }

            return redirect()->route('dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di Lumière Dining.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            AuditLog::create([
                'user_id' => $userId,
                'aksi' => 'Logout',
                'keterangan' => 'User keluar dari sistem',
            ]);
        }

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
