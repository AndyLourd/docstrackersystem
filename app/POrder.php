<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POrder extends Model
{
    protected $fillable = ['po_number','description','status','pr_id'];

    public function prequest(){
    	return $this->belongsTo(PRequest::class,'pr_id');
    }
    public function voucher(){
    	return $this->hasOne(Voucher::class);
    }
}
