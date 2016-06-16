<?php

namespace LaravelWOL;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = array('name', 'ip', 'mac');
}
