<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddCompanyIdTraitToAllModels extends Command
{
    protected $signature = 'add:company-id-trait-to-all';

    protected $description = 'Add 
use App\Traits\CompanyIdTrait; to all models';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $trait = '
use App\Traits\CompanyIdTrait;';

        if (!File::isDirectory($modelsPath)) {
            $this->error('Models directory not found.');
            return;
        }

        $files = File::allFiles($modelsPath);

        foreach ($files as $file) {
            $path = $file->getPathname();
            $contents = File::get($path);

            if (strpos($contents, $trait) === false) {
                $namespacePos = strpos($contents, 'namespace');
                $semicolonPos = strpos($contents, ';', $namespacePos) + 1;
                $useStatementsPos = strpos($contents, 'use', $semicolonPos);

                if ($useStatementsPos === false) {
                    $classPos = strpos($contents, 'class');
                    $contents = substr_replace($contents, "\n$trait\n", $classPos, 0);
                } else {
                    $contents = substr_replace($contents, "\n$trait", $useStatementsPos, 0);
                }

                File::put($path, $contents);
                $this->info("Added CompanyIdTrait to: {$file->getFilename()}");
            } else {
                $this->info("CompanyIdTrait already exists in: {$file->getFilename()}");
            }
        }

        $this->info('CompanyIdTrait addition completed.');
    }
}
