<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
     protected $fillable = [
        'logo',
        'name',
        'email',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'gst_number',
        'lut_number',
        'euid_number',
        'terms_conditions',
        'notes',
        'bank_details',
        'created_by',
        'currency',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }
}
