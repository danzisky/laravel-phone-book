<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',            
        'phonebook_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'addess1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'notes',
    ];
}
