<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetOccupation extends Model
{
    protected $table = 'onet_occupation_data';
    protected $primaryKey = 'onetsoc_code';
    public $incrementing = false;
}
