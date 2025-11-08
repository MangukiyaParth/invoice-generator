<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'description',
        'quantity',
        'hsn',
        'rate',
        'total_amount',
        'igst',
    ];

    public function users()
    {
        return $this->hasMany(Invoice::class, 'invoice_id', 'id');
    }
}
