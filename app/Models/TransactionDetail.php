<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = [];

public function product() {
    return $this->belongsTo(Product::class);
}

// umntuk kurangi stomk
protected static function booted()
{
    static::created(function ($detail) {
        $detail->product->decrement('stock', $detail->quantity);
    });
}
}
