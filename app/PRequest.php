<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRequest extends Model
{
    protected $fillable = [
    	"pr_number",
    	"description",
    	"purpose",
    	"status",
        'create_at',
        'updated_at',
    	"po_id",
    	"voucher_id",
    	"user_id",
        'type',
        'po_number',
        'remarks',

    ];
    public function users(){
    	return $this->hasMany(User::class);
    }
    // public function voucher()
    // {
    //     return $this->hasOne(Voucher::class);
    // }
    public function porder()
    {
        return $this->hasOne(POrder::class);
    }
    public function canvass()
    {
        return $this->hasOne(Canvass::class, 'id');
    }
}
