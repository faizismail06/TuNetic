<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTemplate extends Model
{
    use HasFactory;

    protected $table = 'jadwal_templates';

    protected $fillable = [
        'hari',
        'id_armada',
        'id_rute',
    ];

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id_armada');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'id_rute');
    }

    public function petugasTemplate()
    {
        return $this->hasMany(JadwalTemplatePetugas::class, 'jadwal_template_id');
    }
}
