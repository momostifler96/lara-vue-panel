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
    protected $name = 'lvp:install {name}';

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

        $this->info('Intall composer packages');
        $composer_pakage = new Process(['composer', 'require', 'laravel/breeze', '--dev']);
        $composer_pakage->run();
        if ($composer_pakage->isSuccessful()) {
            $this->info('Laravel Breeze installed successfully.');
            $breez_p = new Process(['php', 'artisan', 'breeze:install', 'vue', '--typescript']);
            $breez_p->run();
            $this->info('Inertia vue typescript installed successfully.');
            $this->info('Intall npm packages');
            $npm_pakage = new Process(['npm', 'install', '--save', 'vue3-apexcharts primevue cropperjs @primevue/themes @headlessui/vue @vuepic/vue-datepicker vue-awesome-paginate maska pinia pinia-plugin-persistedstate']);
            $npm_pakage->run();
            if ($npm_pakage->isSuccessful()) {
                $this->info('Inertia installed successfully.');
            }
            $npm_dev_pakage = new Process(['npm', 'install', '--dev', 'sass']);
            $npm_pakage->run();
            if ($npm_pakage->isSuccessful()) {
                $this->info('Inertia installed successfully.');
            }

            $this->makeDirectory(base_path('app/LVP/Panels'));
            $this->makeDirectory(base_path('app/LVP/Pages'));
            $this->makeDirectory(base_path('app/LVP/Resources'));

            $panel = $this->ask('type default panel name :');
            if ($panel) {
                $this->call('lvp:create-panel', ['name' => ucfirst($panel)]);
            }


        } else {
            $this->info('Laravel Breeze not installed.');
        }
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

}
