<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    use HasFactory;

    protected $table = 'master_data';

    protected $primaryKey = 'id_master'; // Karena id tidak menggunakan default "id"

    protected $fillable = [
        'id_user',
        'id_sampah',
    ];

    /**
     * Relasi ke User.
     * Satu master data terkait dengan satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke Sampah.
     * Satu master data terkait dengan satu jenis sampah.
     */
    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'id_sampah');
    }
}
