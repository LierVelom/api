<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',  // Thêm trường cart_id
        'voucher',
        'status',
        'amount',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
