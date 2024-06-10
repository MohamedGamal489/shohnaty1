<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCar extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function accessories()
    {
        return $this->hasMany(DriverCarAccessories::class,'car_id');
    }
}
