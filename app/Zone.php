<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    //
    protected $fillable = [
		'name',
    ];

    public function users()
    {
    	return $this->hasMany(User::class);
    }
    public function offices(){
    	return $this->hasMany(Office::class);
    }
    public function signatories(){
        return $this->hasMany(Signatory::class);
    }
}
