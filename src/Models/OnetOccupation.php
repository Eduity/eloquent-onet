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
        return $this->abilities_by_importance(3);
    }

    public function abilities_by_importance($atLeast = null)
    {
        return $this->abilities_by_scale('IM', $atLeast);
    }

    public function abilities_by_level($atLeast = null)
    {
        return $this->abilities_by_scale('LV', $atLeast);
    }

    protected function abilities_by_scale($scale, $atLeast = null)
    {
        $query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetAbility::class, 'onet_abilities', 'onetsoc_code', 'element_id');

        if($atLeast !== null) {
            // "Important" only, to avoid duplicate records
            $query->where('scale_id', 'IM');
        }
            
            // O*NET itself seems to only show >3 on their sites
        $query->where('data_value' , '>=', 3)
            
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

        return $query;
    }

    public function alternate_titles()
    {
        return $this
            ->hasMany(\Eduity\EloquentOnet\Models\OnetAlternateOccupationTitle::class, 'onetsoc_code')
        ;
    }

    public function reported_titles()
    {
        return $this
            ->hasMany(\Eduity\EloquentOnet\Models\OnetReportedOccupationTitle::class, 'onetsoc_code')
        ;
    }

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}
