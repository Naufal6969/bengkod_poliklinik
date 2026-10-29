<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JadwalPeriksaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index()
    {
        // Hanya jadwal milik dokter yang sedang login
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())
            ->get()
            ->sortBy(fn ($j) => array_search($j->hari, self::HARI))
            ->values();

        return view('dokter.jadwal-periksa.index', ['jadwalPeriksas' => $jadwals]);
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create', ['hariList' => self::HARI]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari'        => ['required', Rule::in(self::HARI)],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        // id_dokter diambil dari user yang login, bukan dari form
        JadwalPeriksa::create($validated + ['id_dokter' => Auth::id()]);

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil ditambahkan.');
    }

    public function edit(JadwalPeriksa $jadwal)
    {
        $this->pastikanMilikSendiri($jadwal);

        return view('dokter.jadwal-periksa.edit', [
            'jadwal'   => $jadwal,
            'hariList' => self::HARI,
        ]);
    }

    public function update(Request $request, JadwalPeriksa $jadwal)
    {
        $this->pastikanMilikSendiri($jadwal);

        $validated = $request->validate([
            'hari'        => ['required', Rule::in(self::HARI)],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'status'      => ['required', 'boolean'],
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(JadwalPeriksa $jadwal)
    {
        $this->pastikanMilikSendiri($jadwal);

        $jadwal->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil dihapus.');
    }

    // Mencegah dokter A mengubah/menghapus jadwal dokter B lewat URL manual
    private function pastikanMilikSendiri(JadwalPeriksa $jadwal): void
    {
        abort_if((int) $jadwal->id_dokter !== (int) Auth::id(), 403);
    }
}