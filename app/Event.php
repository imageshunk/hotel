<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [ 'start_date','end_date', 'name', 'rate', 'room_id' ];
}
