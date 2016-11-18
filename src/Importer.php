<?php

namespace Eduity\EloquentOnet;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Importer {

    private $console;

    public function __construct()
    {
        $this->console = new ConsoleOutput();
    }

	protected function downloadRemoteFile($fileUrl)
	{
		$this->console->write("Downloading " . $fileUrl . "...");
        $filename = basename($fileUrl);
        $filepath = 'onet/' . $filename;

        if (Storage::exists($filepath)) {
            // Already downloaded.
            $this->console->write(" <info>already available.</info>");
            $this->console->writeln("");
            return;
        }

        $contents = file_get_contents($fileUrl, 'r');
        Storage::put($filepath, $contents);

        if (Storage::has($filepath)) {
            $this->console->write(" <info>saved.</info>");
            $this->console->writeln("");
        }
	}
}