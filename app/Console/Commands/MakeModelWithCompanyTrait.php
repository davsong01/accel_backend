<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;

class MakeModelWithCompanyTrait extends Command
{
    protected $signature = 'make:model-with-trait {name}';
    protected $description = 'Create a new model with HasFactory and CompanyIdTrait traits';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        // Call the default make:model command
        Artisan::call('make:model', ['name' => $this->argument('name')]);

        // Get the path of the generated model file
        $modelPath = app_path('Models/' . $this->argument('name') . '.php');

        if ($this->filesystem->exists($modelPath)) {
            $this->insertTraits($modelPath);
            $this->info('Model created successfully with HasFactory and CompanyIdTrait traits.');
        } else {
            $this->error('Model creation failed. Please check the model name and try again.');
        }
    }

    protected function insertTraits($modelPath)
    {
        // Read the contents of the model file
        $content = $this->filesystem->get($modelPath);

        // Insert the traits into the model
        $content = str_replace('use HasFactory;', 'use HasFactory, CompanyIdTrait;', $content);

        // Insert the correct import for the CompanyIdTrait
        $content = preg_replace('/namespace (.*);/', "namespace $1;\n\nuse App\\Traits\\CompanyIdTrait;", $content);

        // Save the modified content back to the model file
        $this->filesystem->put($modelPath, $content);
    }
}
