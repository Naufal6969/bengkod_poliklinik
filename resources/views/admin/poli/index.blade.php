<x-layouts.app title="Manajemen Poli">
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <h1 class="text-2xl font-bold text-slate-800">Data Poli</h1>

        <a href="{{ route('admin.poli.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2d4499] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1e2d6b]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            Tambah Poli
        </a>
    </div>

    @include('admin.partials.feedback')

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[36rem] text-left text-sm">
                <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama Poli</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($polis as $poli)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $poli->nama_poli }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $poli->keterangan ?: '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.poli.edit', $poli) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="m16 4 4 4M4 20l4-.8L19.2 8a2.1 2.1 0 0 0-3-3L5 16.2 4 20Z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.poli.destroy', $poli) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus poli ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M3 6h18M8 6V4h8v2m3 0-.8 14H5.8L5 6m4 4v6m6-6v6"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-14 text-center text-slate-400">
                                <svg class="mx-auto mb-3 block h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M4 5h16v14H4zM8 9h8m-8 4h8m-8 3h5"></path>
                                </svg>
                                Belum ada data poli
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>

    <div class="mt-6">{{ $polis->links() }}</div>
</x-layouts.app>
