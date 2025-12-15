<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'kelas_id'
    ];

    // Relasi belongsTo ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Tambahkan relasi berikut:
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
