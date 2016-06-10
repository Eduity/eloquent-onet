<?php

namespace Eduity\EloquentOnet;

use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class OnetImporter
{
    private $console;

    public function __construct()
    {
        $this->console = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    protected $tables = ['content_model_reference', 'job_zone_reference', 'occupation_data', 'scales_reference', 'ete_categories', 'level_scale_anchors', 'occupation_level_metadata', 'survey_booklet_locations', 'task_categories', 'work_context_categories', 'abilities', 'education_training_experience', 'interests', 'job_zones', 'knowledge', 'skills', 'task_statements', 'task_ratings', 'work_activities', 'work_context', 'work_styles', 'work_values', 'green_occupations', 'green_task_statements', 'iwa_reference', 'dwa_reference', 'tasks_to_dwas', 'green_dwa_reference', 'tasks_to_green_dwas', 'emerging_tasks', 'career_changers_matrix', 'career_starters_matrix', 'unspsc_reference', 'tools_and_technology', 'alternate_titles', 'sample_of_reported_titles'];

    public function downloadLatestArchive()
    {
        $this->console->write("Downloading " . config('eloquent-onet.download_url') . "...");
        $filename = basename(config('eloquent-onet.download_url'));
        $filepath = 'onet/' . $filename;

        if (Storage::exists($filepath)) {
            // Already downloaded.
            $this->console->write(" <info>already available.</info>");
            $this->console->writeln("");
            return;
        }

        $contents = file_get_contents(config('eloquent-onet.download_url'), 'r');
        Storage::put($filepath, $contents);

        if (Storage::has($filepath)) {
            $this->console->write(" <info>saved.</info>");
            $this->console->writeln("");
        }
    }

    public function importLatestArchive()
    {
        $archive = $this->determineLatestArchive();
        $directory = storage_path('app/onet');

        // Make or clean the directory
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0775, true);
        }

        // Unzip .sql files
        Zipper::make($archive['filepath'])
            ->extractTo($directory);

        $this->importDataFromStorage($archive['info']['filename']);

        File::delete($directory);
    }

    public function determineLatestArchive()
    {
        $files = File::files(storage_path('app/onet'));

        $files = collect($files)->map(function ($item) {
            return [
                'filepath' => $item,
                'last_modified' => filemtime($item),
                'info' => pathinfo($item),
            ];
        })->sortByDesc('last_modified');

        return $files->first();
    }

    public function importDataFromStorage($directory)
    {
        $files = File::files(storage_path('app/onet/' . $directory));

        if (count($files)) {
            $this->console->writeln('Importing ' . count($files) . ' O*NET tables...');
            $progress = new ProgressBar($this->console, count($files));
            foreach ($files as $filepath) {
                if (pathinfo($filepath)['extension'] === 'sql') {
                    $filename = pathinfo($filepath)['filename'];
                    $tableName = substr($filename, 3);
                    $importNumber = intval(substr($filename, 0, 2));

                    if (!Schema::hasTable($tableName)) {
                        // $this->console->writeln('Importing "' . $filename . '"...');
                        DB::unprepared(file_get_contents($filepath));
                    }
                }
                $progress->advance();
            }
            $progress->finish();
        }
    }

    public function renameTables()
    {
        foreach ($this->tables as $table) {
            Schema::rename($table, 'onet_' . $table);
        }
    }
}
