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
        $query->where('data_value' , '>=', $atLeast)
            
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

    public function activities()
    {
        return $this->activities_by_importance(2.95);
    }

    public function activities_by_importance($atLeast = null)
    {
        return $this->activities_by_scale('IM', $atLeast);
    }

    public function activities_by_level($atLeast = null)
    {
        return $this->activities_by_scale('LV', $atLeast);
    }

    protected function activities_by_scale($scale, $atLeast = null)
    {
        $query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetWorkActivity::class, 'onet_work_activities', 'onetsoc_code', 'element_id');

        if($atLeast !== null) {
            // "Important" only, to avoid duplicate records
            $query->where('scale_id', 'IM');
        }
            
            // O*NET itself seems to only show >3 on their sites
        $query->where('data_value' , '>=', $atLeast)
            
            // If they recommend it be suppressed, let it.
            ->where('recommend_suppress', 'N')

            // Include extra data from `onet_work_activities`
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

    public function changers()
    {
        return $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetOccupation::class, 'onet_career_changers_matrix', 'onetsoc_code', 'related_onetsoc_code')
            ->withPivot('related_index')
            ->orderBy('onet_career_changers_matrix.related_index');
    }

    public function starters()
    {
        return $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetOccupation::class, 'onet_career_starters_matrix', 'onetsoc_code', 'related_onetsoc_code')
            ->withPivot('related_index')
            ->orderBy('onet_career_starters_matrix.related_index');
    }

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}
