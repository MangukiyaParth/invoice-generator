<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'company',
        'customer',
        'terms',
        'currency',
        'created_by',
        'max_number',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function company_name()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }


    public function customer_name()
    {
        return $this->belongsTo(Customer::class, 'customer', 'id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
}
