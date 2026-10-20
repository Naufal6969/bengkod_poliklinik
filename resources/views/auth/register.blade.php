<x-layouts.guest title="Register">
    <section class="w-full max-w-[480px] rounded-2xl bg-white p-7 shadow-xl shadow-blue-950/10 ring-1 ring-slate-200 sm:p-10">
        <header class="mb-7 text-center">
            @if (file_exists(public_path('images/logo-bengkot.png')))
                <img
                    src="{{ asset('images/logo-bengkot.png') }}"
                    alt="Logo Bengkod"
                    class="mx-auto mb-4 block h-[60px] w-[60px] rounded-2xl object-cover"
                >
            @else
                <div class="mx-auto mb-4 flex h-[60px] w-[60px] items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700" aria-label="Logo Poliklinik sementara">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M12 4v16M4 12h16"></path>
                    </svg>
                </div>
            @endif
            <h1 class="mb-1 text-2xl font-extrabold text-[#1e2d6b]">Poliklinik</h1>
            <p class="text-sm text-slate-500">Buat akun baru</p>
        </header>

        @if ($errors->any())
            <div role="alert" class="mb-5 flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m15 9-6 6m0-6 6 6"></path>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M5 21a7 7 0 0 1 14 0"></path>
                    </svg>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap..." autocomplete="name" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="m3 7 9 6 9-6"></path>
                    </svg>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email..." autocomplete="email" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="2.5"></circle>
                    </svg>
                    <input id="alamat" type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Masukkan alamat..." autocomplete="street-address" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="no_hp" class="mb-1.5 block text-sm font-semibold text-slate-700">No. HP</label>
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                            <path d="M11 18h2"></path>
                        </svg>
                        <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" placeholder="No. HP..." autocomplete="tel" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>
                <div>
                    <label for="no_ktp" class="mb-1.5 block text-sm font-semibold text-slate-700">No. KTP</label>
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <circle cx="9" cy="11" r="2"></circle>
                            <path d="M6 16c.8-1.5 4.2-1.5 5 0m3-5h4m-4 4h4"></path>
                        </svg>
                        <input id="no_ktp" type="text" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="No. KTP..." inputmode="numeric" minlength="16" maxlength="16" autocomplete="off" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            <div>
                <label for="password_reg" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="4" y="10" width="16" height="11" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                    </svg>
                    <input id="password_reg" type="password" name="password" placeholder="Password..." autocomplete="new-password" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    <button type="button" data-password-toggle="password_reg" aria-controls="password_reg" aria-label="Tampilkan password" aria-pressed="false" class="rounded p-1 text-slate-400 transition hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg data-eye class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-eye-off class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                            <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a13.7 13.7 0 0 1-3.1 3.9M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10.8 10.8 0 0 0 4.1-.8"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirm" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="4" y="10" width="16" height="11" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                    </svg>
                    <input id="password_confirm" type="password" name="password_confirmation" placeholder="Ulangi password..." autocomplete="new-password" required class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    <button type="button" data-password-toggle="password_confirm" aria-controls="password_confirm" aria-label="Tampilkan password" aria-pressed="false" class="rounded p-1 text-slate-400 transition hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg data-eye class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-eye-off class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                            <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a13.7 13.7 0 0 1-3.1 3.9M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10.8 10.8 0 0 0 4.1-.8"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-[#2d4499] to-[#5267ce] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-900/20 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-200">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M19 8v6m3-3h-6"></path>
                </svg>
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-[#2d4499] hover:text-indigo-700">Login</a>
        </p>
    </section>
</x-layouts.guest>
