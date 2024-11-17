<?php

namespace LVP\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use LVP\Facades\LVPCurrentPanel;
use LVP\Facades\Panel;
use URL;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
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

        $this->registerPanelsProviders();
        $this->loadCommands();

        // $this->load();
    }

    public function boot()
    {

        URL::forceScheme(env('APP_SCHEME', 'http'));
        $this->publishes([
            __DIR__ . '/../config/laravue-panel.php' => config_path('laravue-panel.php'),
        ]);
        $this->bootPanels();

    }

    protected function registerPanelsProviders()
    {

        // Chemin vers le dossier contenant les providers personnalisés
        $customProvidersPath = app_path('Providers/Lvp');
        // Récupère tous les fichiers PHP du dossier
        $providerFiles = File::glob($customProvidersPath . '/*.php');

        $panels = [];
        foreach ($providerFiles as $providerFile) {
            // Obtenir le nom de la classe du provider

            $providerClass = 'App\\Providers\\Lvp\\' . basename($providerFile, '.php');
            // Vérifie si la classe existe et enregistre le provider
            if (class_exists($providerClass)) {
                $panels[] = $providerClass;
                $this->app->register($providerClass);
            }
        }
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

        $filesystem = new Filesystem;
        $commandsPath = __DIR__ . '/../Commands';

        $commandFiles = $filesystem->glob($commandsPath . '/*.php');

        $commands = [];

        foreach ($commandFiles as $file) {
            $fileContents = $filesystem->get($file);

            if (
                preg_match('/namespace (.+);/', $fileContents, $namespaceMatches) &&
                preg_match('/class (\w+)/', $fileContents, $classMatches)
            ) {
                $namespace = $namespaceMatches[1];
                $class = $classMatches[1];
                $commands[] = $namespace . '\\' . $class;
            }
        }

        // Register all the commands
        $this->commands($commands);
    }


    protected function bootPanels()
    {

    }
    protected function loadPanels()
    {

        // Cache::forget("lvp-panels");
        $panels_cache = [];
        if (empty($panel_cache)) {
            $filesystem = new Filesystem;

            $panelsPath = app_path('LVP/Panels');

            $panelFiles = $filesystem->glob($panelsPath . '/*.php');

            foreach ($panelFiles as $file) {
                $fileContents = $filesystem->get($file);
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
                    $panels_cache[$panel_instance->getId()] = $panel_instance;

                }
                // Cache::forever("lvp-panels", $this->panels);
            }
        }
        $this->panels = $panels_cache;

    }
}
