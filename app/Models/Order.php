<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Menentukan primary key yang baru
    protected $primaryKey = 'order_id';

    // Jika primary key bukan auto-increment, set properti berikut
    public $incrementing = true;


    protected $fillable = [
        'user_id',
        'product_id',
        'nama',
        'lokasi',
        'no_handphone',
        'order_date',
        'info_tambahan',
        'status',
        'harga',
    ];

    

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi dengan model Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Relasi dengan model Tukang
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }

    public function installments()
    {
    return $this->hasMany(Installment::class, 'order_id', 'order_id');
    }


}
