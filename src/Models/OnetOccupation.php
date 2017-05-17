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

    public function abilities_by_importance($atLeast = 0)
    {
        return $this->abilities_by_scale('IM', $atLeast);
    }

    public function abilities_by_level($atLeast = 0)
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

    public function skills()
    {
        return $this->skills_by_importance(0);
    }

    public function skills_by_importance($atLeast = null)
    {
        return $this->skills_by_scale('IM', $atLeast);
    }

    public function skills_by_level($atLeast = null)
    {
        return $this->skills_by_scale('LV', $atLeast);
    }

    protected function skills_by_scale($scale, $atLeast = null)
    {
        $query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetSkill::class, 'onet_skills', 'onetsoc_code', 'element_id');

        if($atLeast !== null) {
            // "Important" only, to avoid duplicate records
            $query->where('scale_id', 'IM');
        }
            
            // O*NET itself seems to only show >3 on their sites
        $query->where('data_value' , '>=', $atLeast)
            
            // If they recommend it be suppressed, let it.
            ->where('recommend_suppress', 'N')

            // Include extra data from `onet_skills`
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

    public function meta()
    {
        return $this
            ->hasMany(\Eduity\EloquentOnet\Models\OnetOccupationMetadata::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function tasks()
    {
        return $this->hasMany(\Eduity\EloquentOnet\Models\OnetTask::class, 'onetsoc_code');
    }

    public function tools_and_technology()
    {
        return $this->hasMany(\Eduity\EloquentOnet\Models\OnetToolOrTechnology::class, 'onetsoc_code');
    }

    public function work_context()
    {
        return $this->work_context_by_context();
    }

    public function work_context_by_context($atLeast = null)
    {
        return $this->work_context_by_scale('CX', $atLeast);
    }

    protected function work_context_by_scale($scale, $atLeast = null)
    {
        $query = $this
            ->belongsToMany(\Eduity\EloquentOnet\Models\OnetWorkContext::class, 'onet_work_context', 'onetsoc_code', 'element_id');

        if($atLeast !== null) {
            // "Important" only, to avoid duplicate records
            $query->where('scale_id', $scale);
            $query->where('data_value', '>=', $atLeast);
        }
            
        $query
            // If they recommend it be suppressed, let it.
            ->where('recommend_suppress', 'N')

            // Include extra data from `onet_work_activities`
            ->withPivot([
                'category',
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
    public function getNaicsAttribute()
    {
        return $this->meta()->byItem('naics')->orderBy('percent');
    }
}
