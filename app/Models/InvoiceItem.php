<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'hsn',
        'rate',
        'total_amount',
        'tax_type',
        'igst',
        'sgst',
        'cgst',
    ];

    public function users()
    {
        return $this->hasMany(Invoice::class, 'invoice_id', 'id');
    }
}
