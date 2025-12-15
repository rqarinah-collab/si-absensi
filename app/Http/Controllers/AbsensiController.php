<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon; // Digunakan untuk format tanggal

class AbsensiController extends Controller
{
    /**
     * Menampilkan form pemilihan kelas dan tanggal (sekaligus menampilkan tabel siswa jika sudah dipilih).
     */
    public function index()
    {
        // Mencari semua kombinasi unik dari tanggal dan kelas_id yang sudah ada absensinya.
        $riwayatAbsensi = Absensi::select('tanggal')
            ->selectRaw('COUNT(DISTINCT siswa_id) as jumlah_siswa_diabsen')
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.id')
            ->selectRaw('kelas_id')
            ->groupBy('tanggal', 'kelas_id')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Ambil data Kelas untuk mapping nama kelas
        $kelas = Kelas::all()->keyBy('id');

        return view('absensi.index', compact('riwayatAbsensi', 'kelas'));
    }

    /**
     * Menampilkan detail absensi untuk tanggal dan kelas tertentu.
     */
    public function showAbsensi($tanggal, $kelas_id)
    {
        $namaKelas = Kelas::findOrFail($kelas_id)->nama_kelas;

        // Ambil semua absensi untuk kelas dan tanggal yang spesifik
        $detailAbsensi = Absensi::where('tanggal', $tanggal)
            ->whereHas('siswa', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })
            ->with('siswa')
            ->orderBy(Siswa::select('nama_siswa')->whereColumn('siswas.id', 'absensis.siswa_id'))
            ->get();

        if ($detailAbsensi->isEmpty()) {
            return redirect()->route('absensi.index')->with('error', 'Data absensi tidak ditemukan.');
        }

        return view('absensi.show', compact('detailAbsensi', 'tanggal', 'namaKelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        // Data default untuk tampilan awal
        return view('absensi.create', compact('kelas'));
    }

    /**
     * Menampilkan tabel siswa untuk di absen (handle setelah pemilihan kelas/tanggal).
     */
    public function tampilAbsensi(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        $kelas_id = $request->kelas_id;
        $tanggal = $request->tanggal;

        // Ambil data siswa di kelas yang dipilih
        $siswas = Siswa::where('kelas_id', $kelas_id)->orderBy('nama_siswa')->get();

        // Ambil data absensi yang sudah ada untuk tanggal dan kelas ini (untuk pre-fill radio button)
        $absensiHariIni = Absensi::where('tanggal', $tanggal)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        // Ambil data lain yang dibutuhkan view
        $namaKelas = Kelas::find($kelas_id)->nama_kelas;
        $kelas = Kelas::orderBy('nama_kelas')->get(); // Diperlukan untuk dropdown kembali

        return view('absensi.create', compact('kelas', 'siswas', 'absensiHariIni', 'tanggal', 'kelas_id', 'namaKelas'));
    }

    /**
     * Menyimpan data absensi siswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date_format:Y-m-d',
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'required|in:Hadir,Izin,Alpa',
        ]);

        $tanggal = $request->tanggal;
        $kelas_id = $request->kelas_id;
        $kehadiranData = $request->kehadiran;

        // Proses penyimpanan absensi
        foreach ($kehadiranData as $siswaId => $status) {
            // Menggunakan updateOrCreate untuk menangani entri baru atau pembaruan status yang sudah ada
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status_kehadiran' => $status,
                ]
            );
        }

        $tanggalFormatted = Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y');

        // Redirect kembali ke form pemilihan (untuk mengabsen hari/kelas lain)
        return redirect()->route('absensi.create')->with(
            'success',
            "Absensi untuk kelas **" . Kelas::find($kelas_id)->nama_kelas . "** pada tanggal **$tanggalFormatted** berhasil disimpan/diperbarui."
        );
        // Jika Anda ingin tetap di tabel yang sama setelah simpan:
        /*
        return redirect()->route('absensi.tampil')->withInput([
            'kelas_id' => $kelas_id, 
            'tanggal' => $tanggal,
        ])->with('success', "...");
        */
    }
}
