<?php

namespace Eduity\EloquentOnet\Tests;

use Eduity\EloquentOnet\Models\OnetOccupation;
use Eduity\EloquentOnet\Tests\Cases\TestCase;

class OccupationChangersTest extends TestCase {

	/** @test */
	public function career_changers_are_accurate()
	{
		$knownOccupationTitles = collect(['Computer Systems Analysts','Information Security Analysts','Computer Programmers','Software Developers, Applications','Web Developers','Database Administrators','Computer Network Architects','Software Quality Assurance Engineers and Testers','Computer Systems Engineers/Architects','Geographic Information Systems Technicians'])->sort();

		$shouldMatch = OnetOccupation::find('15-1133.00')->changers->pluck('title')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownOccupationTitles->values()->toArray(), $shouldMatch->values()->toArray());
	}
}