<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    //
    protected $fillable = [
        'value_open','date_open','value_previous_close', 'value_close','date_close','value_cash','value_card','observation',
    ];
}
