<?php

namespace Eduity\EloquentOnet\Tests;

use Eduity\EloquentOnet\Models\OnetOccupation;
use Eduity\EloquentOnet\Tests\Cases\TestCase;
use Illuminate\Support\Facades\Schema;

class OccupationAbilitiesTest extends TestCase {

	/** @test */
	public function ceo_abilities_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/11-1011.00#Abilities
		$knownAbilities = collect(['Oral Comprehension', 'Oral Expression', 'Written Comprehension', 'Deductive Reasoning', 'Speech Clarity', 'Speech Recognition', 'Written Expression', 'Inductive Reasoning', 'Problem Sensitivity', 'Fluency of Ideas', 'Near Vision', 'Originality', 'Information Ordering', 'Category Flexibility', 'Flexibility of Closure', 'Mathematical Reasoning', 'Number Facility', 'Perceptual Speed', 'Speed of Closure', 'Visualization', 'Far Vision', 'Memorization', 'Selective Attention', 'Time Sharing'])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('11-1011.00')->abilities->pluck('element_name')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownAbilities->values()->toArray(), $shouldMatch->values()->toArray());
	}

	/** @test */
	public function photographic_process_worker_abilities_are_correct()
	{
		// Copied from http://www.onetonline.org/link/summary/51-9151.00#Abilities
		$knownAbilities = collect(['Near Vision', 'Control Precision', 'Oral Comprehension', 'Visual Color Discrimination', 'Selective Attention', 'Written Comprehension', 'Arm-Hand Steadiness', 'Oral Expression', 'Problem Sensitivity', 'Finger Dexterity', 'Information Ordering', 'Manual Dexterity', 'Category Flexibility', 'Inductive Reasoning'])->sort();

		// Grab the ability records for Chief Executives, sorted by name.
		$shouldMatch = OnetOccupation::find('51-9151.00')->abilities->pluck('element_name')->sort();

		// The basic arrays should equal each other.
		$this->assertEquals($knownAbilities->values()->toArray(), $shouldMatch->values()->toArray());
	}

	/** @test */
	public function abilities_can_be_sorted_by_scale()
	{
		// Retrieve the records and sort them using the database.
		$sortedAsc = OnetOccupation::find('11-1011.00')->abilities()->orderBy('data_value', 'asc')->get();
		$sortedDesc = OnetOccupation::find('11-1011.00')->abilities()->orderBy('data_value', 'desc')->get();

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