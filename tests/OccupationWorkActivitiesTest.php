<?php

namespace Eduity\EloquentOnet\Tests;

use Eduity\EloquentOnet\Models\OnetAbility;
use Eduity\EloquentOnet\Models\OnetOccupation;
use Eduity\EloquentOnet\Tests\Cases\TestCase;
use Illuminate\Support\Facades\Schema;

class OccupationWorkActivitiesTest extends TestCase {

	/** @test */
	public function ceo_activities_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/11-1011.00#WorkActivities
		$knownActivities = collect(["Making Decisions and Solving Problems","Communicating with Supervisors, Peers, or Subordinates","Getting Information","Communicating with Persons Outside Organization","Developing and Building Teams","Guiding, Directing, and Motivating Subordinates","Developing Objectives and Strategies","Establishing and Maintaining Interpersonal Relationships","Monitoring and Controlling Resources","Analyzing Data or Information","Judging the Qualities of Things, Services, or People","Resolving Conflicts and Negotiating with Others","Evaluating Information to Determine Compliance with Standards","Identifying Objects, Actions, and Events","Interacting With Computers","Organizing, Planning, and Prioritizing Work","Interpreting the Meaning of Information for Others","Updating and Using Relevant Knowledge","Processing Information","Coaching and Developing Others","Coordinating the Work and Activities of Others","Thinking Creatively","Staffing Organizational Units","Monitor Processes, Materials, or Surroundings","Selling or Influencing Others","Provide Consultation and Advice to Others","Estimating the Quantifiable Characteristics of Products, Events, or Information","Scheduling Work and Activities","Performing Administrative Activities","Training and Teaching Others"])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('11-1011.00')->activities->pluck('element_name')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownActivities->values()->toArray(), $shouldMatch->values()->toArray());
	}

	/** @test */
	public function photographic_process_worker_activities_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/51-9151.00#WorkActivities
		$knownActivities = collect(["Controlling Machines and Processes","Communicating with Supervisors, Peers, or Subordinates","Monitor Processes, Materials, or Surroundings","Establishing and Maintaining Interpersonal Relationships","Getting Information","Inspecting Equipment, Structures, or Material","Making Decisions and Solving Problems","Organizing, Planning, and Prioritizing Work","Updating and Using Relevant Knowledge","Interacting With Computers","Handling and Moving Objects","Judging the Qualities of Things, Services, or People","Identifying Objects, Actions, and Events","Repairing and Maintaining Electronic Equipment","Thinking Creatively","Coordinating the Work and Activities of Others","Performing for or Working Directly with the Public","Processing Information"])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('51-9151.00')->activities->pluck('element_name')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownActivities->values()->toArray(), $shouldMatch->values()->toArray());
	}

	/** @test */
	public function activities_can_be_sorted_by_scale()
	{
		// Retrieve the records and sort them using the database.
		$sortedAsc = OnetOccupation::find('11-1011.00')->activities()->orderBy('data_value', 'asc')->get();
		$sortedDesc = OnetOccupation::find('11-1011.00')->activities()->orderBy('data_value', 'desc')->get();

		// First, neither should have any non-numeric values where expected.
		$this->assertEquals(0, $sortedAsc->filter(function ($item) {
			return (float) $item->pivot->data_value <= 0;
		})->count());

		// Ensure the arrays are not equal.
		$this->assertNotEquals(
			$sortedAsc->pluck('pivot.data_value')->values()->toArray(), 
			$sortedDesc->pluck('pivot.data_value')->values()->toArray()
		);

		// Once re-sorting both of the arrays, they now should be identical.
		$this->assertEquals(
			$sortedAsc->sortBy('pivot.data_value')->pluck('pivot.data_value')->values()->toArray(),
			$sortedDesc->sortBy('pivot.data_value')->pluck('pivot.data_value')->values()->toArray()
		);
	}
}