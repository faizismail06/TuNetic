<?php
// Model Province
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'reg_provinces'; // Pastikan nama tabel benar
    protected $fillable = ['name']; // Kolom yang bisa diisi mass assignment

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}
