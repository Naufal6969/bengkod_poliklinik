@php
    $isEditing = isset($poli);
@endphp

@include('admin.partials.feedback')

<form action="{{ $isEditing ? route('admin.poli.update', $poli) : route('admin.poli.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="nama_poli" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Poli</label>
        <input id="nama_poli" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('nama_poli') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="keterangan" class="mb-1.5 block text-sm font-semibold text-slate-700">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="4"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">{{ old('keterangan', $poli->keterangan ?? '') }}</textarea>
        @error('keterangan') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Poli' }}
        </button>
        <a href="{{ route('admin.poli.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    </div>
</form>
