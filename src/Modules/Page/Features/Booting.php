<?php

namespace LVP\Modules\Page\Features;

use LVP\Modules\Panel\Panel;

trait Booting
{

    public function register(Panel $panelProvider)
    {
        $this->setupPanel($panelProvider);
        $this->buildLabelsAndTitles();
    }

    public function boot(Panel $panelProvider)
    {

    }
}