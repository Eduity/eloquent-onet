<?php

namespace Eduity\EloquentOnet\Models;

use Eduity\EloquentOnet\Models\OnetOccupation;
use Illuminate\Database\Eloquent\Model;

class OnetOccupationMetadata extends Model
{
    protected $table = 'onet_occupation_level_metadata';
    protected $itemKeys = [
    	'collection_mode'			=> 'Data Collection Mode',
		'how_long_at_current_job'	=> 'How Long at Current Job',
		'industry_division'			=> 'Industry Division (Major Group Codes (SIC) within Division in parentheses)',
		'naics'						=> 'NAICS Sector',
		'case_completeness'			=> 'O*NET-SOC Case Completeness Rate',
		'employee_response'			=> 'O*NET-SOC Employee Response Rate',
		'establishment_response'	=> 'O*NET-SOC Establishment Response Rate',
		'total_completes'			=> 'Total Completes for O*NET-SOC',
		'expert_response_rate'		=> 'O*NET-SOC Eligible Expert Response Rate',
    ];
    public $incrementing = false;

    /** RELATIONSHIPS */
    public function occupation()
    {
    	return $this->belongsTo(OnetOccupation::class, 'onetsoc_code', 'onetsoc_code');
    }

    /** SCOPES */
    public function scopeByItem($query, $itemName)
    {
    	$key = isset($this->itemKeys[$itemName]) ? $this->itemKeys[$itemName] : $itemName;

    	$query->where('item', $itemName);
    }

    /** ACCESSORS AND MUTATORS */
}
