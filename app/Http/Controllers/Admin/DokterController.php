<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(): View
    {
        return view('admin.dokter.index', [
            'dokters' => Dokter::query()
                ->with(['poli', 'user'])
                ->orderBy('nama_dokter')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.dokter.create', [
            'polis' => Poli::query()->orderBy('nama_poli')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'poli_id' => ['required', 'integer', 'exists:poli,id'],
            'nama_dokter' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'name' => $validated['nama_dokter'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'dokter',
                'id_poli' => $validated['poli_id'],
            ]);

            $user->dokter()->create([
                'poli_id' => $validated['poli_id'],
                'nama_dokter' => $validated['nama_dokter'],
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        });

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter dan akun login berhasil ditambahkan.');
    }

    public function edit(Dokter $dokter): View
    {
        $dokter->load('user');

        return view('admin.dokter.edit', [
            'dokter' => $dokter,
            'polis' => Poli::query()->orderBy('nama_poli')->get(),
        ]);
    }

    public function update(Request $request, Dokter $dokter): RedirectResponse
    {
        $validated = $request->validate([
            'poli_id' => ['required', 'integer', 'exists:poli,id'],
            'nama_dokter' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dokter->user_id)],
            'password' => [$dokter->user_id === null ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($dokter, $validated): void {
            $user = $dokter->user;

            if ($user === null) {
                $user = User::create([
                    'name' => $validated['nama_dokter'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'dokter',
                    'id_poli' => $validated['poli_id'],
                ]);
            } else {
                $user->update([
                    'name' => $validated['nama_dokter'],
                    'email' => $validated['email'],
                    'role' => 'dokter',
                    'id_poli' => $validated['poli_id'],
                    ...(! empty($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
                ]);
            }

            $dokter->update([
                'user_id' => $user->id,
                'poli_id' => $validated['poli_id'],
                'nama_dokter' => $validated['nama_dokter'],
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        });

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Dokter $dokter): RedirectResponse
    {
        if ($dokter->jadwalPeriksas()->exists()) {
            return back()->with('error', 'Dokter tidak dapat dihapus karena masih memiliki jadwal periksa.');
        }

        DB::transaction(function () use ($dokter): void {
            $user = $dokter->user;
            $dokter->delete();
            $user?->delete();
        });

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter dan akun login berhasil dihapus.');
    }
}
