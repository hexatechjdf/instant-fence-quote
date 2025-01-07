<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fence extends Model
{
    use HasFactory;

    protected $fillable = ['fence_name' ,'user_id' , 'category_id','fence_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(PriceFit::class);
    }

    public function ft_available()
    {
        return $this->hasMany(FenceFtAvailable::class);
    }

}
