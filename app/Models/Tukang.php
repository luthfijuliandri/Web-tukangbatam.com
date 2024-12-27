<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;

    protected $table = 'tukang';

    protected $primaryKey = 'id_tukang'; // Pastikan primary key sesuai dengan nama di tabel

    public $timestamps = true; // Sesuaikan dengan tabel Anda, set ke false jika tidak menggunakan timestamps

    protected $fillable = [
        'nama_tukang',
        'nomorhp_tukang',
        'status_tukang',
    ];

    public function keahlian()
    {
        return $this->hasMany(KeahlianTukang::class, 'tukang_id');
    }
}
