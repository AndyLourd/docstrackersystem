<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Canvass extends Model
{
    protected $fillable = ['mayors_permit','clearance','status','pr_id'];

    public function prequest()
    {
    	return $this->belongsTo(PRequest::class, 'pr_id');
    }
}
