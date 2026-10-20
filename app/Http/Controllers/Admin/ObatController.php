<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(): View
    {
        return view('admin.obat.index', [
            'obats' => Obat::query()->orderBy('nama_obat')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.obat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255', 'unique:obat,nama_obat'],
            'kemasan' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        Obat::create($validated);

        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat): View
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255', Rule::unique('obat', 'nama_obat')->ignore($obat)],
            'kemasan' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $obat->update($validated);

        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        if ($obat->detailPeriksas()->exists()) {
            return back()->with('error', 'Obat tidak dapat dihapus karena sudah digunakan dalam detail pemeriksaan.');
        }

        $obat->delete();

        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil dihapus.');
    }
}
