<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
    	'code',
    	'description',
    ];
    protected $hidden = [ 'hidden_id' ];

    public function zone(){
    	return $this->belongsTo(Zone::class);
    }
    public function signatories(){
    	return $this->hasMany(Signatory::class);
    }
}
