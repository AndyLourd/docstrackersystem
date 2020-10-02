<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    protected $fillable = [ 'name','signature','designation_id','office_id','zone_id' ];

    public function designation(){
    	return $this->belongsTo(Designation::class);
    }
    public function office(){
    	return $this->belongsTo(Office::class);
    }
    public function zone(){
    	return $this->belongsTo(Zone::class);
    }
}
