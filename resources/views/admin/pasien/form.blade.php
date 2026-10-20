@php
    $isEditing = isset($pasien);
@endphp

@include('admin.partials.feedback')

<form action="{{ $isEditing ? route('admin.pasien.update', $pasien) : route('admin.pasien.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="no_rm" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor Rekam Medis</label>
        <input id="no_rm" name="no_rm" value="{{ old('no_rm', $pasien->no_rm ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('no_rm') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="nama_pasien" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Pasien</label>
        <input id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien', $pasien->nama_pasien ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('nama_pasien') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
        @error('alamat') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="no_hp" class="mb-1.5 block text-sm font-semibold text-slate-700">No. HP</label>
        <input id="no_hp" name="no_hp" value="{{ old('no_hp', $pasien->no_hp ?? '') }}" maxlength="20"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('no_hp') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="tanggal_lahir" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($pasien?->tanggal_lahir) ? $pasien->tanggal_lahir->format('Y-m-d') : '') }}" max="{{ now()->toDateString() }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('tanggal_lahir') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jenis_kelamin" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            <option value="">Pilih jenis kelamin</option>
            <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Pasien' }}
        </button>
        <a href="{{ route('admin.pasien.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    </div>
</form>
