<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceFit extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function fence()
    {
        return $this->belongsTo(Fence::class);
    }
}
