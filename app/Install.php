<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Install extends Model
{
    protected $table = 'installation';
    protected $fillable = ['installed'];
}
