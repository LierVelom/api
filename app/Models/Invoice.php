<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
