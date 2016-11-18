<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetToolOrTechnology extends Model
{
    protected $table = 'onet_tools_and_technology';
    protected $primaryKey = null;
    public $incrementing = false;

    /** RELATIONSHIPS */
    public function occupation()
    {
    	return $this->belongsTo(OnetOccupation::class, 'onetsoc_code', 'onetsoc_code');
    }

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}
