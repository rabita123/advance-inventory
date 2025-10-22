<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBill extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
