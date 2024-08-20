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
        Artisan::call('make:model', ['name' => $this->argument('name')]);

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
        $content = $this->filesystem->get($modelPath);

        $content = str_replace('use HasFactory;', 'use HasFactory, CompanyIdTrait;', $content);

        $content = preg_replace('/namespace (.*);/', "namespace $1;\n\nuse App\\Traits\\CompanyIdTrait;", $content);

        $this->filesystem->put($modelPath, $content);
    }
}
