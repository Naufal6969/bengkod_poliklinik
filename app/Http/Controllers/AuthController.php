<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password yang dimasukkan salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->dashboard($request);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:5000'],
            'no_ktp' => ['required', 'digits:16', 'unique:users,no_ktp'],
            'no_hp' => ['required', 'string', 'regex:/^[0-9]{8,20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated): void {
            $monthPrefix = now()->format('Ym');
            $highestSequence = User::query()
                ->where('role', 'pasien')
                ->where('no_rm', 'like', "{$monthPrefix}-%")
                ->lockForUpdate()
                ->pluck('no_rm')
                ->map(fn (string $recordNumber): int => (int) Str::afterLast($recordNumber, '-'))
                ->max() ?? 0;

            $nextSequence = $highestSequence + 1;

            User::create([
                'name' => $validated['nama'],
                'alamat' => $validated['alamat'],
                'no_ktp' => $validated['no_ktp'],
                'no_hp' => $validated['no_hp'],
                'no_rm' => $monthPrefix.'-'.str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT),
                'role' => 'pasien',
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function dashboard(Request $request): RedirectResponse
    {
        $dashboardRoute = match ($request->user()->role) {
            'admin' => 'admin.dashboard',
            'dokter' => 'dokter.dashboard',
            'pasien' => 'pasien.dashboard',
            default => abort(403),
        };

        return redirect()->route($dashboardRoute);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
