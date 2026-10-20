@php
    $isEditing = isset($obat);
@endphp

@include('admin.partials.feedback')

<form action="{{ $isEditing ? route('admin.obat.update', $obat) : route('admin.obat.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="nama_obat" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Obat</label>
        <input id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('nama_obat') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="kemasan" class="mb-1.5 block text-sm font-semibold text-slate-700">Kemasan</label>
        <input id="kemasan" name="kemasan" value="{{ old('kemasan', $obat->kemasan ?? '') }}" maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('kemasan') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="stok" class="mb-1.5 block text-sm font-semibold text-slate-700">Stok</label>
        <input id="stok" type="number" name="stok" value="{{ old('stok', $obat->stok ?? 0) }}" required min="0" step="1"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('stok') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="harga" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga (Rp)</label>
        <input id="harga" type="number" name="harga" value="{{ old('harga', $obat->harga ?? 0) }}" required min="0" step="0.01"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('harga') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Obat' }}
        </button>
        <a href="{{ route('admin.obat.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    </div>
</form>
