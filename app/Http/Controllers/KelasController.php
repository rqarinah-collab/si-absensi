<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas.
     */
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Menyimpan kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kelas.
     */
    public function edit(Kelas $kela) // Menggunakan $kela karena Laravel akan mencari 'kelas'
    {
        return view('kelas.edit', ['kelas' => $kela]);
    }

    /**
     * Memperbarui kelas.
     */
    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelas')->ignore($kela->id),
            ],
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Menghapus kelas.
     */
    public function destroy(Kelas $kela)
    {
        try {
            // Periksa apakah ada siswa yang terkait dengan kelas ini sebelum menghapus
            // Asumsi: Nanti akan ada relasi hasMany di Model Kelas ke Siswa
            if ($kela->siswa()->exists()) {
                return redirect()->route('kelas.index')->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa!');
            }

            $kela->delete();
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah pada database (misalnya constraint)
            return redirect()->route('kelas.index')->with('error', 'Kelas gagal dihapus. Mungkin ada data terkait.');
        }
    }
}
