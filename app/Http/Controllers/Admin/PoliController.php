<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function index(): View
    {
        return view('admin.poli.index', [
            'polis' => Poli::query()->withCount('dokters')->orderBy('nama_poli')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.poli.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:255', 'unique:poli,nama_poli'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Poli::create($validated);

        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil ditambahkan.');
    }

    public function edit(Poli $poli): View
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli): RedirectResponse
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:255', Rule::unique('poli', 'nama_poli')->ignore($poli)],
            'keterangan' => ['nullable', 'string'],
        ]);

        $poli->update($validated);

        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli): RedirectResponse
    {
        if ($poli->dokters()->exists() || User::query()->where('id_poli', $poli->id)->exists()) {
            return back()->with('error', 'Poli tidak dapat dihapus karena masih memiliki dokter.');
        }

        $poli->delete();

        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil dihapus.');
    }
}
