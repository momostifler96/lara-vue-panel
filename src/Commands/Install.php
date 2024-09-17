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

            $notiftable = new Process(['php', 'artisan', 'notification:table']);
            $notiftable->run();

            $this->info('Inertia vue typescript installed successfully.');
            $this->info('Intall npm packages');

            $npm_pakages = [
                'apexcharts',
                'vue3-apexcharts ',
                'primevue ',
                'cropperjs',
                '@primevue/themes',
                '@headlessui/vue',
                'vue-awesome-paginate',
                'maska',
                'pinia',
                'pinia-plugin-persistedstate',
                // 'primevue/dropdown',
                // 'primevue/multiselec',
                '@vuepic/vue-datepicker',
                '@tiptap/vue-3',
                '@tiptap/starter-kit',
                '@tiptap/extension-highlight',
                '@tiptap/extension-text-align',
                '@tiptap/extension-link',
                '@tiptap/extension-underline',
                '@tiptap/extension-dropcursor',
                '@tiptap/extension-placeholder',
                '',
            ];
            foreach ($npm_pakages as $key => $npm_pakage) {
                $npm_pakage_prc = new Process(['npm', 'install', '--save', $npm_pakage]);
                $npm_pakage_prc->run();
                if ($npm_pakage_prc->isSuccessful()) {
                    $this->info($npm_pakage . ' installed successfully.');
                }
            }
            $npm_dev_pakages = [
                'sass',
            ];
            foreach ($npm_dev_pakages as $key => $npm_pakage) {
                $npm_pakage_prc = new Process(['npm', 'install', '--dev', $npm_pakage]);
                $npm_pakage_prc->run();
                if ($npm_pakage_prc->isSuccessful()) {
                    $this->info($npm_pakage . ' installed successfully.');
                }
            }

            $this->makeDirectory(base_path('app/LVP/Panels'));
            $this->makeDirectory(base_path('app/LVP/Pages'));
            $this->makeDirectory(base_path('app/LVP/Resources'));

            $panel = $this->ask('type default panel name :');
            if ($panel) {
                $this->call('lvp:create-panel', ['name' => ucfirst($panel)]);
            }
            $this->info('Laravue panel installed successfully.');


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
