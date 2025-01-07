<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtAvailable extends Model
{
    use HasFactory;

    protected $fillable = ['ft_available_name','user_id'];

    // public function fence()
    // {
    //     return $this->belongsToMany(Fence::class);
    // }

     public function prices()
    {
        return $this->hasMany(PriceFit::class,'ft_available_id');
    }
}
