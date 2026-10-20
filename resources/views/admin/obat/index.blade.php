<x-layouts.app title="Data Obat">
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <h1 class="text-2xl font-bold text-slate-800">Data Obat</h1>
        <a href="{{ route('admin.obat.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2d4499] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1e2d6b]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            Tambah Obat
        </a>
    </div>

    @include('admin.partials.feedback')

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[42rem] text-left text-sm">
                <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama Obat</th>
                        <th class="px-6 py-4">Kemasan</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($obats as $obat)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $obat->nama_obat }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $obat->kemasan ?: '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ number_format($obat->stok, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-slate-500">Rp{{ number_format((float) $obat->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.obat.edit', $obat) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">Edit</a>
                                    <form action="{{ route('admin.obat.destroy', $obat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center text-slate-400">
                                <svg class="mx-auto mb-3 block h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M4 5h16v14H4zM8 9h8m-8 4h8m-8 3h5"></path>
                                </svg>
                                Belum ada data obat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">{{ $obats->links() }}</div>
</x-layouts.app>
