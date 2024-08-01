<?php

namespace LVP\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use LVP\Facades\LVPCurrentPanel;
use LVP\Facades\Panel;

class LVPProvider extends \Illuminate\Support\ServiceProvider
{
    protected $resources = [];
    protected $pages = [];
    protected $panels = [];
    protected $clusters = [];
    protected $widgets = [];
    protected $formFields = [];
    protected $tableColumns = [];

    public function register()
    {

        $this->load();

    }

    public function boot()
    {
        $this->loadPanels();
        // dd($this->panels);
        $this->bootPanels();
    }

    protected function load()
    {
        $this->loadCommands();
        // $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        // $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'lvp');
        // $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'lvp');
    }

    protected function loadCommands()
    {

        // dd(request()->path());
        $filesystem = new Filesystem;
        $commandsPath = __DIR__ . '/../Commands';

        // Get all PHP files in the commands directory
        $commandFiles = $filesystem->glob($commandsPath . '/*.php');

        // dd($commandFiles);
        // Initialize an array to hold the command class names
        $commands = [];

        // Iterate over each file and extract the class name
        foreach ($commandFiles as $file) {
            // Get the file contents
            $fileContents = $filesystem->get($file);

            // Use regex to extract the namespace and class name
            if (
                preg_match('/namespace (.+);/', $fileContents, $namespaceMatches) &&
                preg_match('/class (\w+)/', $fileContents, $classMatches)
            ) {
                $namespace = $namespaceMatches[1];
                $class = $classMatches[1];

                // Combine namespace and class to get the fully qualified class name
                $commands[] = $namespace . '\\' . $class;
            }
        }

        // Register all the commands
        $this->commands($commands);
    }


    protected function bootPanels()
    {
        foreach ($this->panels as $key => $panel) {
            if (str_starts_with(request()->path(), $key)) {
                app()->singleton(LVPCurrentPanel::class, function () use ($key) {
                    return new LVPCurrentPanel($this->panels[$key]);
                });
            }
        }
        // dd(app(LVPCurrentPanel::class)->panel);
        $current_panel = app(LVPCurrentPanel::class);
        if (!empty($current_panel)) {
            $current_panel->panel->boot();
        }
    }
    protected function loadPanels()
    {

        // Cache::forget("lvp-panels");
        $panels_cache = [];
        // dd($panels_cache);
        if (empty($panel_cache)) {
            $filesystem = new Filesystem;

            $panelsPath = app_path('LVP/Panels');

            // Get all PHP files in the pages directory
            $panelFiles = $filesystem->glob($panelsPath . '/*.php');

            // Initialize an array to hold the command class names
            // dd($panelFiles);
            // Iterate over each file and extract the class name
            foreach ($panelFiles as $file) {
                // Get the file contents
                $fileContents = $filesystem->get($file);

                // Use regex to extract the namespace and class name
                if (
                    preg_match('/namespace (.+);/', $fileContents, $namespaceMatches) &&
                    preg_match('/class (\w+)/', $fileContents, $classMatches)
                ) {
                    $namespace = $namespaceMatches[1];
                    $class = $classMatches[1];
                    $panel_class = $namespace . '\\' . $class;
                    /**
                     * @var Panel
                     */

                    $panel_instance = $panel_class::getInstance();
                    $panel_instance->setup();
                    // Combine namespace and class to get the fully qualified class name
                    $panels_cache[$panel_instance->getId()] = $panel_instance;
                    // dump($class, $panel_instance, $panel_instance->getId());

                }

                // Cache::forever("lvp-panels", $this->panels);
            }
        }
        $this->panels = $panels_cache;

    }
}
