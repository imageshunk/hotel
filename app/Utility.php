<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    protected $table = 'utilities';
    public $primaryKey = 'id';
    public $timestamps = true;
}
