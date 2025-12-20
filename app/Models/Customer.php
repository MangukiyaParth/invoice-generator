<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     protected $fillable = [
        'name',
        'nick_name',
        'email',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'gst_number',
        'place_of_supply',
        'created_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }
}
