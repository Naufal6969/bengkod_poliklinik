@php
    $isEditing = isset($jadwalPeriksa);
@endphp

@include('admin.partials.feedback')

<form action="{{ $isEditing ? route('admin.jadwal-periksa.update', $jadwalPeriksa) : route('admin.jadwal-periksa.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="dokter_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Dokter</label>
        <select id="dokter_id" name="dokter_id" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            <option value="">Pilih dokter</option>
            @foreach ($dokters as $dokter)
                <option value="{{ $dokter->id }}" @selected(old('dokter_id', $jadwalPeriksa->dokter_id ?? '') == $dokter->id)>
                    {{ $dokter->nama_dokter }} — {{ $dokter->poli->nama_poli }}
                </option>
            @endforeach
        </select>
        @error('dokter_id') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="hari" class="mb-1.5 block text-sm font-semibold text-slate-700">Hari</label>
        <select id="hari" name="hari" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            <option value="">Pilih hari</option>
            @foreach ($hariOptions as $hari)
                <option value="{{ $hari }}" @selected(old('hari', $jadwalPeriksa->hari ?? '') === $hari)>{{ $hari }}</option>
            @endforeach
        </select>
        @error('hari') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="jam_mulai" class="mb-1.5 block text-sm font-semibold text-slate-700">Jam Mulai</label>
            <input id="jam_mulai" type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($jadwalPeriksa) ? substr($jadwalPeriksa->jam_mulai, 0, 5) : '') }}" required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            @error('jam_mulai') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="jam_selesai" class="mb-1.5 block text-sm font-semibold text-slate-700">Jam Selesai</label>
            <input id="jam_selesai" type="time" name="jam_selesai" value="{{ old('jam_selesai', isset($jadwalPeriksa) ? substr($jadwalPeriksa->jam_selesai, 0, 5) : '') }}" required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
            @error('jam_selesai') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="tarif_periksa" class="mb-1.5 block text-sm font-semibold text-slate-700">Tarif Pemeriksaan (Rp)</label>
        <input id="tarif_periksa" type="number" name="tarif_periksa" value="{{ old('tarif_periksa', $jadwalPeriksa->tarif_periksa ?? 0) }}" required min="0" step="0.01"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        @error('tarif_periksa') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Jadwal' }}
        </button>
        <a href="{{ route('admin.jadwal-periksa.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    </div>
</form>
