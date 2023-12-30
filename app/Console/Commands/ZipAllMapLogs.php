<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use File;
use Storage;

class ZipAllMapLogs extends Command
{
    protected $signature = 'app:zip-map-log-files';

    protected $description = 'Search a local folder for txt files and zip each of them';

    public function handle()
    {
        $folderPath = 'storage/app/logs/map/';
        $filesPath = $files = Storage::disk('public')->allFiles($folderPath);

        // Ensure the provided path is a directory
        if (!is_dir($folderPath)) {
            $this->error('Invalid directory path.');
            return;
        }

        // Get all txt files in the specified folder
        $txtFiles = File::glob($folderPath . '/*.txt');

        if (empty($txtFiles)) {
            $this->info('No txt files found in the specified folder.');
            return;
        }

        //dd($txtFiles);

        foreach ($txtFiles as $txtFile) {
            // Create a unique zip file name based on the txt file name
            $zipFileName = pathinfo($txtFile, PATHINFO_FILENAME) . '.zip';

            // Create a new ZipArchive instance
            $zip = new ZipArchive;

            // Open the zip file for writing
            if ($zip->open($folderPath . '/' . $zipFileName, ZipArchive::CREATE) === true) {
                // Add the txt file to the zip file
                $zip->addFile($txtFile, pathinfo($txtFile, PATHINFO_BASENAME));

                // Close the zip file
                $zip->close();

                $this->info("File '{$txtFile}' zipped successfully.");

                // Delete the original txt file
                File::delete($txtFile);
                $this->info("File '{$txtFile}' deleted.");
            } else {
                $this->error("Failed to create zip file for '{$txtFile}'.");
            }
        }

        $this->info('All txt files zipped successfully.');
    }
}
