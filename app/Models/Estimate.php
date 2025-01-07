<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $guarded = [];
    
     public function user(){
       return $this->belongsTo(User::class,'company_id');
    }

    public function getMainImageAttribute()
    {
      $file_path = 'images/estimates/'.$this->uuid.'.png';
      if(file_exists(public_path($file_path))){
        return '<a href="'.asset($file_path).'" target="_blank" class="text-center"><i class="fa fa-eye" aria-hidden="true"></i></a>';
      }
      $file_path = 'assets/images/404.png';
      return '<a href="'.asset($file_path).'" target="_blank" class="text-center"><img src="'.asset($file_path).'" style="width:50px"></a>';
    }
}
