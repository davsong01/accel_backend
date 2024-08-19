<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddCompanyIdTraitToModels extends Command
{
    protected $signature = 'add:company-id-trait';

    protected $description = 'Add CompanyIdTrait to all models that use HasFactory';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $traitToAdd = 'CompanyIdTrait';
        $useStatementToFind = 'use HasFactory;';

        if (!File::isDirectory($modelsPath)) {
            $this->error('Models directory not found.');
            return;
        }

        $files = File::allFiles($modelsPath);

        foreach ($files as $file) {
            $path = $file->getPathname();
            $contents = File::get($path);

            if (strpos($contents, $traitToAdd) === false) {
                if (strpos($contents, $useStatementToFind) !== false) {
                    // Add CompanyIdTrait to the existing use HasFactory;
                    $newUseStatement = str_replace(
                        $useStatementToFind,
                        'use HasFactory, ' . $traitToAdd . ';',
                        $contents
                    );

                    File::put($path, $newUseStatement);
                    $this->info("Added {$traitToAdd} to: {$file->getFilename()}");
                } else {
                    $this->warn("No HasFactory found in: {$file->getFilename()}");
                }
            } else {
                $this->info("Trait already exists in: {$file->getFilename()}");
            }
        }

        $this->info('Trait addition completed.');
    }
}
