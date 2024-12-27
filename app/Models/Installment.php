<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $primaryKey = 'installment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'installment_number',
        'amount',
        'status',
        'payment_proof',
    ];

    protected $casts = [
        'installment_id' => 'integer', // Gunakan 'integer' untuk tipe angka
        'order_id' => 'integer',
        'installment_number' => 'integer',
        'amount' => 'float',
        'status' => 'string',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
