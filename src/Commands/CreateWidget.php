<?php

namespace LVP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateWidget extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lvp:create-widget';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new LVP widget';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');

        $path = $this->getPath($name);
        $this->makeDirectory($path);
        $this->createResourceFile($name, $path);
        $this->info('LVP resource created successfully.');
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
