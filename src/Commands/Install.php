<?php

namespace LVP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class Install extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lvp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
      
        $this->info('Intallation des ressources');
        $composer_pakage = new Process('composer require laravel/breeze --dev');
        $composer_pakage->run();
        if ($composer_pakage->isSuccessful()) {
            $this->info('Laravel Breeze installed successfully.');
            $this->call('breeze:install vue --typescript');
            $this->info('Inertia vue typescript installed successfully.');
            $npm_pakage = new Process('npm i --save vue3-apexcharts primevue/config @primevue/themes/aura vue-awesome-paginate maska pinia pinia-plugin-persistedstate');
            $npm_pakage->run();
            if ($npm_pakage->isSuccessful()) {
                $this->info('Inertia installed successfully.');
            }
        }else{
            $this->info('Laravel Breeze not installed.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the LVP resource'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the resource already exists'],
        ];
    }

    /**
     * Get the path to where the resource file should be created.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path('app/LVP/Resources/' . $name . 'Resource.php');
    }

    /**
     * Create the directory for the resource if it does not exist.
     *
     * @param string $path
     * @return void
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Create the resource file with the given name and path.
     *
     * @param string $name
     * @param string $path
     * @return void
     */
    protected function createResourceFile($name, $path)
    {
        $stub = $this->files->get(__DIR__ . '/stubs/Resource.stub');

        $stub = str_replace('{{ class }}', $name . 'Resource', $stub);

        $this->files->put($path, $stub);
    }
}
