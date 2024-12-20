<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class,'invoice_products')
                ->withPivot('price','quantity')
                ->withTimestamps();
    }

    protected $fillable=[
        "name","description","price"
    ];
}
