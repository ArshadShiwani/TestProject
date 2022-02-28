<?php

namespace App;
use App\Helper\HasManyRelation;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasManyRelation;

    protected $fillable = [
        'number', 'customer_id', 'date',
        'sub_total','status','terms_and_conditions','invoice_img','discount','total'
   ];




    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerAll()
    {
        return $this->hasMany(Customer::class,'id','customer_id');
    }


     public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function amounts()
    {
        return $this->hasMany(InvoiceAmount::class);
    }


    public function InvoiceAmount()
    {
        return $this->hasMany(InvoiceAmount::class)->sum('amount_pay');;
    }

    public function lastInvoiceAmount()
    {
        return $this->belongsTo(InvoiceAmount::class,'id','invoice_id');
    }

    public function firstInvoiceAmount()
    {
        return $this->hasMany(InvoiceAmount::class,'invoice_id','id');
    }


    public function InvoiceBelongs()
    {
        return $this->hasMany(InvoiceAmount::class,'invoice_id','id');
    }
}
