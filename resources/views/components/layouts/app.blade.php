@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Poliklinik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-bold text-[#1e2d6b]">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700" aria-hidden="true">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 4v16M4 12h16"></path>
                    </svg>
                </span>
                <span>Poliklinik</span>
            </a>

            <div class="flex items-center gap-4">
                <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-8 sm:px-6 lg:flex-row lg:px-8">
        @if (auth()->user()->role === 'admin')
            <aside class="w-full shrink-0 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 lg:w-60">
                <p class="px-3 pb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Menu Admin</p>
                <nav class="flex flex-wrap gap-2 lg:flex-col" aria-label="Navigasi admin">
                    @foreach ([
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.poli.index', 'label' => 'Manajemen Poli'],
                        ['route' => 'admin.dokter.index', 'label' => 'Manajemen Dokter'],
                        ['route' => 'admin.pasien.index', 'label' => 'Manajemen Pasien'],
                        ['route' => 'admin.obat.index', 'label' => 'Manajemen Obat'],
                        ['route' => 'admin.jadwal-periksa.index', 'label' => 'Jadwal Periksa'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'rounded-lg px-3 py-2 text-sm font-semibold transition',
                               'bg-indigo-50 text-indigo-700' => request()->routeIs($item['route']) || ($item['route'] !== 'admin.dashboard' && request()->routeIs(str_replace('.index', '.*', $item['route']))),
                               'text-slate-600 hover:bg-slate-50 hover:text-indigo-700' => ! (request()->routeIs($item['route']) || ($item['route'] !== 'admin.dashboard' && request()->routeIs(str_replace('.index', '.*', $item['route'])))),
                           ])>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>
        @endif

        <section class="min-w-0 flex-1">
            {{ $slot }}
        </section>
    </main>
</body>
</html>
