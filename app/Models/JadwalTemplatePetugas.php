<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTemplatePetugas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_template_petugas';

    protected $fillable = [
        'jadwal_template_id',
        'id_petugas',
        'tugas',
    ];

    public function jadwalTemplate()
    {
        return $this->belongsTo(JadwalTemplate::class, 'jadwal_template_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function getTugasLabelAttribute()
    {
        return $this->tugas == 1 ? 'Driver' : 'Picker';
    }
}
