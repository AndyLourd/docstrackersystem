<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['description', 'status','remarks','po_id','to_id','user_id','type','amount'];

    public function torder(){
    	return $this->belongsTo(TOrder::class,'to_id');
    }
    public function porder(){
    	return $this->belongsTo(POrder::class,'po_id');
    }
    public function user(){
    	return $this->belongsTo(User::class,'user_id');
    }
}
