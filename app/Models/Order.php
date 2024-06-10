<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
