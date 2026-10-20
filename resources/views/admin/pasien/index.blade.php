<x-layouts.app title="Manajemen Pasien">
    @include('admin.partials.feedback')

    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-1 text-sm font-semibold uppercase tracking-wide text-indigo-600">Data Master</p>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Pasien</h1>
            </div>
            <a href="{{ route('admin.pasien.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700">
                Tambah Pasien
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-left text-sm">
                <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-3 py-3">No. RM</th>
                        <th class="px-3 py-3">Nama</th>
                        <th class="px-3 py-3">No. HP</th>
                        <th class="px-3 py-3">Jenis Kelamin</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pasiens as $pasien)
                        <tr>
                            <td class="px-3 py-4 font-semibold text-slate-900">{{ $pasien->no_rm }}</td>
                            <td class="px-3 py-4 text-slate-700">{{ $pasien->nama_pasien }}</td>
                            <td class="px-3 py-4 text-slate-600">{{ $pasien->no_hp ?: '-' }}</td>
                            <td class="px-3 py-4 text-slate-600">{{ ['L' => 'Laki-laki', 'P' => 'Perempuan'][$pasien->jenis_kelamin] ?? '-' }}</td>
                            <td class="px-3 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.pasien.edit', $pasien) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">Ubah</a>
                                    <form action="{{ route('admin.pasien.destroy', $pasien) }}" method="POST" onsubmit="return confirm('Hapus data pasien ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-rose-700 hover:text-rose-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-10 text-center text-slate-500">Belum ada data pasien.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $pasiens->links() }}</div>
    </section>
</x-layouts.app>
