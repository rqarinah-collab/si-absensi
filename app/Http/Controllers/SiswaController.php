<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa.
     */
    public function index()
    {
        // Ambil semua siswa dan muat relasi kelas
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        return view('siswa.index', compact('siswas'));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('kelas'));
    }

    /**
     * Menyimpan siswa baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:15|unique:siswas,nisn',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit siswa.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Memperbarui siswa.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:15',
                Rule::unique('siswas')->ignore($siswa->id),
            ],
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus siswa.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}
