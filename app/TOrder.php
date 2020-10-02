<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TOrder extends Model
{	
	protected $fillable = [
        'id',
    	"to_number",
    	"destination",
    	"inclusive_date",
    	"purpose",
    	"status",
        "remarks"
    ];
    public function voucher(){
    	return $this->hasOne(Voucher::class,'to_id');
    }
    public function tousers(){
        return $this->hasMany(TOUser::class,'to_id');
    }
}
