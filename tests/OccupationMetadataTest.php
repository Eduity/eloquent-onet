<?php

namespace Eduity\EloquentOnet\Tests;

use Eduity\EloquentOnet\Models\OnetOccupation;
use Eduity\EloquentOnet\Tests\Cases\TestCase;

class OccupationMetadataTest extends TestCase {

	/** @test */
	public function metadata_can_be_retrieved()
	{
		$occupation = OnetOccupation::find('15-1131.00');
		// dd($occupation->naics);
	}
}