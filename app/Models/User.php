<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'user_name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function token()
    {
        return JWTAuth::fromUser($this);
    }

    public function otp()
    {
        return $this->hasOne(Otp::class);
    }

    public function loginPhoto():Attribute
    {
        return Attribute::make(function (){
//            $encodedImage=$this->encodedImage()?->image;
            $encodedImage="storage/users/photo/6CsmTojnjU1nlvkBEQcNIf3wHUjyXSval4Y1a152.jpg";
            return $encodedImage ? url($encodedImage):url($this->image);
        });
    }

    public function encodedImage()
    {
        return $this->hasOne(EncodedImage::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function upcomingReorders()
    {
        return $this->hasMany(UpcomingReorder::class);
    }

    public function analyticsPredictions()
    {
        return $this->hasMany(AnalyticsPredictions::class);
    }
}
