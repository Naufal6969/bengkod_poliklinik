<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(): View
    {
        return view('admin.pasien.index', [
            'pasiens' => Pasien::query()->orderBy('nama_pasien')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_rm' => ['required', 'string', 'max:255', 'unique:pasien,no_rm'],
            'nama_pasien' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        ]);

        Pasien::create($validated);

        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function edit(Pasien $pasien): View
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien): RedirectResponse
    {
        $validated = $request->validate([
            'no_rm' => ['required', 'string', 'max:255', Rule::unique('pasien', 'no_rm')->ignore($pasien)],
            'nama_pasien' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        ]);

        $pasien->update($validated);

        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien): RedirectResponse
    {
        if ($pasien->daftarPolis()->exists()) {
            return back()->with('error', 'Pasien tidak dapat dihapus karena masih memiliki pendaftaran poli.');
        }

        $pasien->delete();

        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
