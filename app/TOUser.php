<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TOUser extends Model
{
    protected $fillable = ['user_id','to_id'];

    public function torder(){
		return $this->belongsTo(TOrder::class);
	}
	public function users(){
		return $this->hasMany(User::class);
	}
}
