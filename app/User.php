<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
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
        'name', 'email', 'password','zone_id','office_id','designation_id','user_type','signature','project_id'
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

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
    public function prequest()
    {
        return $this->belongsTo(PRequest::class);
    }
    public function tousers(){
        return $this->belongsTo(TOUser::class,'user_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

}
