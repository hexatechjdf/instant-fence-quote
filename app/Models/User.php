<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ghl_api_key',
        'location',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function categories()
        {
            return $this->hasMany(Category::class,'user_id','id');
        }

    public function fences()
        {
            return $this->hasMany(Fence::class,'user_id','id');
        }

    public function ft_available()
        {
            return $this->hasMany(FtAvailable::class);
        }

    public function sendPasswordResetNotification($token)
    {
        $user = User::where('email',request()->email)->first();

        if($user){
                $url = route('password.email').'?token='.$token;


                 $credetials = [
                    'reason' => 'Password Reset',
                    'name' => $user->name,
                    'email' => $user->email,
                    'link' => $url,
                    'token' => $token
                 ];

                $mail =  sendEmail1($credetials);
         }else{
             return back()->withError('User Not Found against entered email address!');
         }



    }

    public function crmtokenagency()
    {
        return $this->hasOne(CrmToken::class, 'user_id')->where('user_type', 'company');
    }

}
