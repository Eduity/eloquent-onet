<?php

namespace Eduity\EloquentOnet;

use Eduity\EloquentOnet\Importer;

class NaicsImporter extends Importer
{
	public function downloadData()
    {
        $this->downloadRemoteFile(config('eloquent-onet.download_url.naics'));
    }
}