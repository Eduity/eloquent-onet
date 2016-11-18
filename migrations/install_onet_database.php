<?php

use Eduity\EloquentOnet\OnetImporter;
use Illuminate\Database\Migrations\Migration;

class InstallOnetDatabase extends Migration
{
	private $importer;

	public function __construct()
	{
		$this->importer = new OnetImporter;
	}

    public function up()
    {
    	$this->importer->downloadData();
    	$this->importer->importLatestArchive();
    	$this->importer->renameTables();
    }

    public function down()
    {
    	$this->importer->deleteOnetTables();
    }
}