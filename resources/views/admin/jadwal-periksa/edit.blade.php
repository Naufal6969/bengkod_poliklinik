<x-layouts.app title="Ubah Jadwal Periksa">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-md sm:p-8">
        <p class="mb-1 text-sm font-semibold uppercase tracking-wide text-indigo-600">Jadwal Dokter</p>
        <h1 class="mb-6 text-2xl font-bold text-slate-900">Ubah Jadwal Periksa</h1>
        @include('admin.jadwal-periksa.form', ['jadwalPeriksa' => $jadwalPeriksa, 'dokters' => $dokters, 'hariOptions' => $hariOptions])
    </section>
</x-layouts.app>
