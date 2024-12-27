<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianTukang extends Model
{
    use HasFactory;

    protected $table = 'keahlian_tukang';

    protected $fillable = [
        'tukang_id',
        'keahlian',
    ];

    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
}
