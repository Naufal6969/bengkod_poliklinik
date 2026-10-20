@php
    $isEditing = isset($dokter);
    $linkedUser = $isEditing ? $dokter->user : null;
@endphp

@include('admin.partials.feedback')

<form action="{{ $isEditing ? route('admin.dokter.update', $dokter) : route('admin.dokter.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="nama_dokter" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Dokter</label>
        <input id="nama_dokter" name="nama_dokter" value="{{ old('nama_dokter', $dokter->nama_dokter ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('nama_dokter') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="poli_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Poli</label>
        <select id="poli_id" name="poli_id" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            <option value="">Pilih poli</option>
            @foreach ($polis as $poli)
                <option value="{{ $poli->id }}" @selected(old('poli_id', $dokter->poli_id ?? '') == $poli->id)>{{ $poli->nama_poli }}</option>
            @endforeach
        </select>
        @error('poli_id') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">{{ old('alamat', $dokter->alamat ?? '') }}</textarea>
        @error('alamat') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="no_hp" class="mb-1.5 block text-sm font-semibold text-slate-700">No. HP</label>
        <input id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp ?? '') }}" maxlength="20"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('no_hp') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email Login Dokter</label>
        <input id="email" type="email" name="email" value="{{ old('email', $linkedUser?->email ?? '') }}" required maxlength="255"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('email') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Password {{ $linkedUser ? '(kosongkan jika tidak diubah)' : '' }}
        </label>
        <input id="password" type="password" name="password" {{ $linkedUser ? '' : 'required' }} minlength="8" autocomplete="new-password"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('password') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" {{ $linkedUser ? '' : 'required' }} minlength="8" autocomplete="new-password"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Dokter' }}
        </button>
        <a href="{{ route('admin.dokter.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    </div>
</form>
