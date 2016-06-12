<?php

namespace Eduity\EloquentOnet\Models;

use Illuminate\Database\Eloquent\Model;

class OnetAbility extends \Eduity\EloquentOnet\Models\OnetContent
{
    protected $table = 'onet_content_model_reference';
    protected $primaryKey = 'element_id';

	/**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new \Eduity\EloquentOnet\Scopes\AbilityScope);
    }

    /** RELATIONSHIPS */
    public function occupations()
    {
    	return $this->occupations_by_importance();
    }

    public function occupations_by_importance($atLeast = '3')
    {
    	return $this->occupations_by_scale('IM', $atLeast);
    }

    public function occupations_by_level()
    {
    	return $this->occupations_by_scale('LV');
    }

    public function occupations_by_scale($scale, $atLeast = null)
    {
    	
    	$query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetOccupation::class, 'onet_abilities', 'element_id', 'onetsoc_code');

        if($atLeast) {
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
            ->orderBy('data_value', 'desc')
        ;

        return $query;
    }

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}