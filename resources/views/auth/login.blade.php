<x-layouts.guest title="Login">
    <section class="w-full max-w-[420px] rounded-2xl bg-white p-7 shadow-xl shadow-blue-950/10 ring-1 ring-slate-200 sm:p-10">
        <header class="mb-8 text-center">
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
            <p class="text-sm text-slate-500">Masuk ke akun Anda</p>
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

        @if (session('success'))
            <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="m3 7 9 6 9-6"></path>
                    </svg>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email..."
                        autocomplete="email"
                        required
                        class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400"
                    >
                </div>
            </div>

            <div>
                <label for="password_login" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 transition focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="4" y="10" width="16" height="11" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                    </svg>
                    <input
                        id="password_login"
                        type="password"
                        name="password"
                        placeholder="Masukkan password..."
                        autocomplete="current-password"
                        required
                        class="min-w-0 grow border-0 bg-transparent py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400"
                    >
                    <button
                        type="button"
                        data-password-toggle="password_login"
                        aria-controls="password_login"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        class="rounded p-1 text-slate-400 transition hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
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

            <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-[#2d4499] to-[#5267ce] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-900/20 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-200">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <path d="m10 17 5-5-5-5m5 5H3"></path>
                </svg>
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-[#2d4499] hover:text-indigo-700">Register</a>
        </p>
    </section>
</x-layouts.guest>
