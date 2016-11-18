<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetTask extends Model
{
    protected $table = 'onet_task_statements';
    protected $primaryKey = 'task_id';

    /** RELATIONSHIPS */
    public function occupation()
    {
        return $this->belongsTo(\App\Models\Onet\OnetOccupation::class, 'onetsoc_code');
    }

    /** SCOPES */
    public function scopeOfType($query, $type)
    {
        return $query->where('task_type', $type);
    }

    /** ACCESSORS AND MUTATORS */

    /** HOOKS */
    public function newPivot(\Illuminate\Database\Eloquent\Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof \Eduity\EloquentOnet\Models\OnetOccupation) {
            return new OnetOccupationTaskRatingPivot($parent, $attributes, $table, $exists);
        }
     
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
