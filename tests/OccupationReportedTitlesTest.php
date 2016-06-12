<?php

use Eduity\EloquentOnet\Models\OnetOccupation;
use Eduity\EloquentOnet\Tests\Cases\TestCase;

class OccupationReportedTitlesTest extends TestCase {
	/** @test */
	public function ceo_titles_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/11-1011.00
		$knownTitles = collect(['Chief Diversity Officer (CDO)', 'Chief Executive Officer (CEO)', 'Chief Financial Officer (CFO)', 'Chief Nursing Officer', 'Chief Operating Officer (COO)', 'Executive Director', 'Executive Vice President (EVP)', 'Operations Vice President', 'President', 'Vice President'])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('11-1011.00')->reported_titles->pluck('reported_job_title')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownTitles->values()->toArray(), $shouldMatch->values()->toArray());
	}

	/** @test */
	public function programmer_titles_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/11-1011.00
		$knownTitles = collect(['Analyst Programmer', 'Applications Developer', 'Computer Programmer', 'Computer Programmer Analyst', 'Internet Programmer', 'Java Developer', 'Programmer', 'Programmer Analyst', 'Software Developer', 'Web Programmer'])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('15-1131.00')->reported_titles->pluck('reported_job_title')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownTitles->values()->toArray(), $shouldMatch->values()->toArray());
	}
}