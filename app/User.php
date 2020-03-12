<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'phone', 'name', 'family', 'active', 'news', 'rools', 'title_company', 'stars', 'countStar',
        'url_avatar', 'timestamp', 'email_verify_code','email_verified_at', 'phone_verify_code', 'phone_verified_at',
        'password', 'workFieldArticle', 'workFieldService', 'cash', 'workLocal', 'theme', 'userRole', 'articleBrand_id', 'serviceBrand_id',
        'numberFree', 'numberPey', 'about', 'saateKariFrom', 'saateKariTo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function article(){
        return $this->hasMany(ArticleCreator::class, 'user_id', 'id');
    }

    public function upgradeLogo(){
        return $this->hasMany(UpgradeLogo::class, 'user_id', 'id');
    }

    public function location(){
        return $this->hasMany(Location::class, 'user_id', 'id');
    }

    public function activeFreeCall(){
        return $this->hasMany(ActiveFreeCall::class, 'user_id', 'id');
    }

    public function callHistory(){
        return $this->hasMany(CallHistory::class, 'user_id', 'id');
    }

    public function service(){
        return $this->hasMany(ServiceCreator::class, 'user_id', 'id');
    }

    public function numbers(){
        return $this->hasMany(BusinessNumber::class, 'user_id', 'id');
    }

    public function logHistory(){
        return $this->hasMany(LogHistory::class, 'user_id', 'id');
    }

    public function articleBrand()
    {
        return $this->belongsTo(ArticleBrand::class, 'articleBrand_id', 'id');
    }

    public function serviceBrand()
    {
        return $this->belongsTo(ServiceBrand::class, 'serviceBrand_id', 'id');
    }

    public function usrRole()
    {
        return $this->belongsTo(AccessLevel::class, 'userRole', 'id');
    }

    public function articleArea()
    {
        return $this->belongsTo(ArticleArea::class, 'workFieldArticle', 'id');
    }

    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class, 'workFieldService', 'id');
    }

    public function rateCustomer()
    {
        return $this->belongsTo(RateCustomerToReseller::class, 'user_id', 'id');
    }

    public function sign()
    {
        return $this->belongsTo(Sign::class, 'user_id', 'id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'user_id', 'id');
    }
}
