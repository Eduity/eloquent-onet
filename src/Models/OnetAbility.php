<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetAbility extends \Eduity\EloquentOnet\Models\OnetContentType
{
    protected $table = 'onet_content_model_reference';
    protected $primaryKey = 'element_id';
    public $content_id_range = '1.A';

    /** RELATIONSHIPS */
    public function occupations()
    {
    	return $this->occupations_by_importance(3);
    }

    public function occupations_by_importance($atLeast = null)
    {
    	return $this->occupations_by_scale('IM', $atLeast);
    }

    public function occupations_by_level($atLeast = null)
    {
    	return $this->occupations_by_scale('LV', $atLeast);
    }

    protected function occupations_by_scale($scale, $atLeast = null)
    {
    	$query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetOccupation::class, 'onet_abilities', 'element_id', 'onetsoc_code');

        if($atLeast !== null) {
            $query
	            // O*NET itself seems to only show >3 on their sites
	        	->where('data_value' , '>=', $atLeast);
        }
            
        $query
            // "Important" only, to avoid duplicate records
            ->where('scale_id', $scale)

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

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}