<?php

namespace LVP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateResource extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lvp:create-resource {name} {--model=any} {--create-model=any} {--type=simple} {--force}';
    protected $name = 'lvp:create-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new LVP resource';

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
        $model = $this->option('model');
        $create_model = $this->option('create-model');
        // $type = $this->option('type');
        // $force = $this->option('force');
        // dd($create_model);
        if ($create_model != 'any') {
            $this->call('make:model', [
                'name' => $create_model,
                '--migration' => true,
            ]);
            $model = $create_model;
        } else {
            $model = ($model == 'any') ? '' : $model;
        }

        $path = $this->getPath($name);
        $this->makeDirectory($path);
        $this->createResourceFile($name, $model, $path);
        Cache::forget('lvp-menus-' . 'admin');
        Cache::forget('lvp-menus-' . 'user-admin');
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
    protected function createResourceFile($name, $model, $path)
    {
        $stub = $this->files->get(__DIR__ . '/../../resources/stubs/Resource.stub');

        $model = $model == '' ? '\Illuminate\Database\Eloquent\Model::class' : '\App\Models\\' . ucfirst($model) . '::class';
        $namespace_and_class_name = getNamespaceAndClassName($name);
        $base_route = str($namespace_and_class_name[1])->kebab()->plural()->lower();
        $stub_data = str_replace(['{{namespace}}', '{{class}}', '{{model}}', '{{base_route}}'], [$namespace_and_class_name[0], $namespace_and_class_name[1], $model, $base_route], $stub);

        $this->files->put($path, $stub_data);
    }
}
