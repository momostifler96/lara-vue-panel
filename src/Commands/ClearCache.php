<?php

namespace LVP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ClearCache extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lvp:clear-menu-cache {--all=true} {--resource=} {--panel=} {--menu=} {--page=}';
    protected $name = 'lvp:clear-menu-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear panel nav menus cache';

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
        try {
            $resource = $this->option('resource');
            $page = $this->option('page');
            $panel = $this->option('panel');
            $menu = $this->option('menu');
            $all = $this->option('all');
            if (!empty($menu)) {
                Cache::forget('lvp-menus-' . $menu);
            }
            if (!empty($panel)) {
                Cache::forget('lvp-panel-' . $panel);
            }
            if (!empty($page)) {
                Cache::forget('lvp-page-' . $page);
            }
            if (!empty($resource)) {
                Cache::forget('lvp-resource-' . $resource);
            }
            if (!empty($all) && $all == 'true') {
                Cache::forget('lvp-panels');
                Cache::forget('lvp-menus-admin');
                Cache::forget('lvp-menus-user');
            }

            // else {
            //     Cache::forget('lvp-menus-' . 'admin');
            // }
            $this->info('LVP panel menu cache cleaned successfully.');
        } catch (\Throwable $th) {
            $this->error('LVP panel menu cache not cleaned.');
        }
    }
}
