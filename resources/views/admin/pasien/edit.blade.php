<x-layouts.app title="Ubah Pasien">
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <p class="mb-1 text-sm font-semibold uppercase tracking-wide text-indigo-600">Data Master</p>
        <h1 class="mb-6 text-2xl font-bold text-slate-900">Ubah Pasien</h1>
        @include('admin.pasien.form', ['pasien' => $pasien])
    </section>
</x-layouts.app>
