<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'Mayor_name', 'City_hall_address', 'Phone', 'Fax', 'E-mail', 'Web_address', 'Image', 'latitude', 'longitude'];
}
