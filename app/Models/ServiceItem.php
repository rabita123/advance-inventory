<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(ServiceBill::class);
    }

}
