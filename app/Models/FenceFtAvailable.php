<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FenceFtAvailable extends Model
{
    use HasFactory;
    protected $fillable = [ 'ft_available_id' , 'fence_id' ,'price' ,'range','is_min_fee'];

    public function ft_available()
    {
        return $this->belongsTo(FtAvailable::class);
    }

    public function fence()
    {
        return $this->belongsTo(Fence::class);
    }
    
     public function prices()
    {
        return $this->hasMany(PriceFit::class,'ft_available_id','ft_available_id');
    }

}
