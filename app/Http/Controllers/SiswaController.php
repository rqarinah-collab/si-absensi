<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar semua kelas beserta siswanya. (METHOD INI YANG HILANG/RUSAK)
     */
    public function index(): View
    {
        // Ambil semua data Kelas, beserta relasi Siswa-nya (Eager Loading)
        $kelas = Kelas::with('siswa')
            ->orderBy('nama')
            ->get();

        return view('siswa.index', [
            'kelas' => $kelas,
        ]);
    }

    /**
     * Menampilkan form untuk menambah siswa baru.
     */
    public function create(): View
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('siswa.create', [
            'kelas' => $kelas,
        ]);
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string|max:100',
            'nisn' => 'nullable|string|max:20|unique:siswas,nisn',
        ]);

        Siswa::create($validated);

        return redirect(route('siswa.index'))->with('status', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit siswa.
     */
    public function edit(Siswa $siswa): View
    {
        $kelas = Kelas::orderBy('nama')->get();

        return view('siswa.edit', [
            'siswa' => $siswa,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Menyimpan perubahan data siswa ke database.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string|max:100',
            // Validasi NISN: harus unik, kecuali untuk NISN siswa yang sedang diedit
            'nisn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswas', 'nisn')->ignore($siswa->id),
            ],
        ]);

        $siswa->update($validated);

        return redirect(route('siswa.index'))->with('status', 'Siswa berhasil diperbarui!');
    }


    /**
     * Menghapus siswa tertentu dari database.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect(route('siswa.index'))->with('status', 'Siswa berhasil dihapus.');
    }
}
