<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
     protected $fillable = [
        'product', 'unit_price', 'qty'
    ];
}
