<?php

namespace LVP\Facades;

use LVP\Facades\Panel;

class LVPCurrentPanel
{
    public Panel $panel;

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }
}
