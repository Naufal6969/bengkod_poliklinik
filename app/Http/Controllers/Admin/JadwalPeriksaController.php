<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalPeriksaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(): View
    {
        return view('admin.jadwal-periksa.index', [
            'jadwalPeriksas' => JadwalPeriksa::query()
                ->with('dokter')
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.jadwal-periksa.create', [
            'dokters' => Dokter::query()->with('poli')->orderBy('nama_dokter')->get(),
            'hariOptions' => self::HARI,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dokter_id' => ['required', 'integer', 'exists:dokter,id'],
            'hari' => ['required', Rule::in(self::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tarif_periksa' => ['required', 'numeric', 'min:0'],
        ]);
        $this->validateUniqueStartTime($request, $validated);
        $this->validateNoOverlappingSchedule($validated);

        JadwalPeriksa::create($validated);

        return redirect()->route('admin.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil ditambahkan.');
    }

    public function edit(JadwalPeriksa $jadwalPeriksa): View
    {
        return view('admin.jadwal-periksa.edit', [
            'jadwalPeriksa' => $jadwalPeriksa,
            'dokters' => Dokter::query()->with('poli')->orderBy('nama_dokter')->get(),
            'hariOptions' => self::HARI,
        ]);
    }

    public function update(Request $request, JadwalPeriksa $jadwalPeriksa): RedirectResponse
    {
        $validated = $request->validate([
            'dokter_id' => ['required', 'integer', 'exists:dokter,id'],
            'hari' => ['required', Rule::in(self::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tarif_periksa' => ['required', 'numeric', 'min:0'],
        ]);
        $this->validateUniqueStartTime($request, $validated, $jadwalPeriksa);
        $this->validateNoOverlappingSchedule($validated, $jadwalPeriksa);

        $jadwalPeriksa->update($validated);

        return redirect()->route('admin.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(JadwalPeriksa $jadwalPeriksa): RedirectResponse
    {
        if ($jadwalPeriksa->daftarPolis()->exists()) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah digunakan dalam pendaftaran pasien.');
        }

        $jadwalPeriksa->delete();

        return redirect()->route('admin.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil dihapus.');
    }

    /**
     * @param  array{dokter_id: int|string, hari: string, jam_mulai: string}  $validated
     */
    private function validateUniqueStartTime(Request $request, array $validated, ?JadwalPeriksa $jadwalPeriksa = null): void
    {
        $request->validate([
            'jam_mulai' => [
                Rule::unique('jadwal_periksa', 'jam_mulai')
                    ->where('dokter_id', $validated['dokter_id'])
                    ->where('hari', $validated['hari'])
                    ->ignore($jadwalPeriksa),
            ],
        ], [
            'jam_mulai.unique' => 'Dokter sudah memiliki jadwal pada hari dan jam mulai tersebut.',
        ]);
    }

    /**
     * @param  array{dokter_id: int|string, hari: string, jam_mulai: string, jam_selesai: string, tarif_periksa: int|float|string}  $validated
     */
    private function validateNoOverlappingSchedule(array $validated, ?JadwalPeriksa $jadwalPeriksa = null): void
    {
        $hasOverlap = JadwalPeriksa::query()
            ->where('dokter_id', $validated['dokter_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->when($jadwalPeriksa !== null, fn ($query) => $query->whereKeyNot($jadwalPeriksa->id))
            ->exists();

        if ($hasOverlap) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Jadwal dokter tidak boleh bertumpang tindih pada hari yang sama.',
            ]);
        }
    }
}
