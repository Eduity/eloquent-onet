<?php

use Eduity\EloquentOnet\NaicsImporter;
use Illuminate\Database\Migrations\Migration;

class InstallNaicsData extends Migration
{
	private $importer;

	public function __construct()
	{
		$this->importer = new NaicsImporter;
	}

    public function up()
    {
    	$this->importer->downloadData();
    	// $this->importer->importLatestData();
    }

    public function down()
    {
    	$this->importer->deleteNaicsTables();
    }
}