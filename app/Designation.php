<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
    	'code',
    	'description',
    ];

    protected $hidden = [ 'hidden_id' ];

    public function signatories(){
    	return $this->hasMany(Signatory::class);
    }
    public function users()
    {
    	return $this->hasMany(User::class);
    }
}
