<?php

namespace LVP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreatePanel extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lvp:create-panel {name} {--id=same}';
    protected $name = 'lvp:create-pnel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new LVP panel';

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


        $id = $this->option('id');
        if ($id == 'same') {
            $id = str($name)->kebab()->lower();
        }
        $path = $this->getPath($name);
        $this->makeDirectory($path);
        $this->createResourceFile($name, $id, $path);

        $this->info('LVP panel created successfully.');
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
        return base_path('app/LVP/Panels/' . $name . 'Panel.php');
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
    protected function createResourceFile($name, $id, $path)
    {
        $stub = $this->files->get(__DIR__ . '/../../resources/stubs/Panel.stub');

        $panel_id = str($id ?? $name)->kebab()->lower();
        $namespace_and_class_name = getNamespaceAndClassName($name);
        $stub_data = str_replace(['{{namespace}}', '{{class}}', '{{id}}',], [$namespace_and_class_name[0], $namespace_and_class_name[1], $panel_id], $stub);

        $this->files->put($path, $stub_data);
    }
}
