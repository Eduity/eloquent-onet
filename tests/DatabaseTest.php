<?php

namespace Eduity\EloquentOnet\Tests;

use Eduity\EloquentOnet\Tests\Cases\DatabaseTestCase;
use Illuminate\Support\Facades\Schema;

class DatabaseTest extends DatabaseTestCase {

    protected $tables = ['content_model_reference', 'job_zone_reference', 'occupation_data', 'scales_reference', 'ete_categories', 'level_scale_anchors', 'occupation_level_metadata', 'survey_booklet_locations', 'task_categories', 'work_context_categories', 'abilities', 'education_training_experience', 'interests', 'job_zones', 'knowledge', 'skills', 'task_statements', 'task_ratings', 'work_activities', 'work_context', 'work_styles', 'work_values', 'green_occupations', 'green_task_statements', 'iwa_reference', 'dwa_reference', 'tasks_to_dwas', 'green_dwa_reference', 'tasks_to_green_dwas', 'emerging_tasks', 'career_changers_matrix', 'career_starters_matrix', 'unspsc_reference', 'tools_and_technology', 'alternate_titles', 'sample_of_reported_titles'];

	/** @test */
	public function onet_tables_exist()
	{
		foreach($this->tables as $table) {
			$tableName = 'onet_' . $table;
			$this->assertTrue(Schema::hasTable($tableName), 'Table "' . $tableName . '" does not exist.');
		}
	}

}