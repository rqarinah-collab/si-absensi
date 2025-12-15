<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        // ... (fillable Siswa lainnya)
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
