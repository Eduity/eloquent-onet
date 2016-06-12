<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetOccupation extends Model
{
    protected $table = 'onet_occupation_data';
    protected $primaryKey = 'onetsoc_code';
    public $incrementing = false;

    /** RELATIONSHIPS */
    public function abilities()
    {
        // TODO: need to represent the LV (Level) Scale.  This only takes IM (Importance) into account.
        return $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetAbility::class, 'onet_abilities', 'onetsoc_code', 'element_id')

            // "Important" only, to avoid duplicate records
            ->where('scale_id', 'IM')
            
            // O*NET itself seems to only show >3 on their sites
            ->where('data_value' , '>=', 3)
            
            // If they recommend it be suppressed, let it.
            ->where('recommend_suppress', 'N')

            // Include extra data from `onet_abilities`
            ->withPivot([
                'data_value',
                'n',
                'standard_error',
                'lower_ci_bound',
                'upper_ci_bound',
                'recommend_suppress',
                'not_relevant',
                'date_updated',
                'domain_source',
            ])
        ;
    }

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}
