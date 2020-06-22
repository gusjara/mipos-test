<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $fillable = [
        'balance_id','name','value',
    ];

    // relation to balance
    public function balance(){
        return $this->belongsTo(Balance::class, 'balance_id');
    }
}
