<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanPetugas extends Model
{
    use HasFactory;

    protected $table = 'penugasan_petugas';

    protected $fillable = [
        'id_petugas',
        'id_jadwal_operasional',
        'tugas',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function jadwalOperasional()
    {
        return $this->belongsTo(JadwalOperasional::class, 'id_jadwal_operasional');
    }

    public function getTugasAttribute($value)
    {
        $map = [
            1 => 'Driver',
            2 => 'Picker',
        ];
        return $map[$value] ?? 'Undefined';
    }

    public function setTugasAttribute($value)
    {
        if (!in_array($value, [1, 2])) {
            throw new \InvalidArgumentException('Tugas harus 1 (driver) atau 2 (picker)');
        }

        $this->attributes['tugas'] = $value;
    }
}
