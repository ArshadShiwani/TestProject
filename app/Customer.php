<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     protected $table = 'customers';
     protected $fillable = [
          'client', 'nici', 'ntn_strn',
          'phone','email','status','type'

     ];




    public function invoices()
     {
         return $this->hasMany(Invoice::class);
     }

    public function InvoiceAmount()
    {
            return $this->hasMany(InvoiceAmount::class);

    }




     protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->attributes['client'];
    }
}
