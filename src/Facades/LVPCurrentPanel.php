<?php

namespace LVP\Facades;

use LVP\Providers\PanelProvider;


class LVPCurrentPanel
{
    public PanelProvider $panel;

    public function __construct(PanelProvider $panel)
    {
        $this->panel = $panel;
    }
}
