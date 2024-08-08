<?php
namespace LVP\Support;

class PanelMenuGroup
{
    protected string $name;
    protected string $icon;
    protected int $position;
    protected bool $dismisable;
    public function __construct(string $name, string $path, string $icon = 'stack', int $position = 0, bool $dismisable = false)
    {

        $this->name = $name;
        $this->icon = $icon;
        $this->position = $position;
        $this->dismisable = $dismisable;
    }
    public static function make(string $name, string $path, string $icon = 'stack', int $position = 0, bool $dismisable = false)
    {
        return new PanelNavLink($name, $path, $icon, $position, $dismisable);
    }

    public function getMenu()
    {
        return [
            'label' => $this->name,
            'position' => $this->position,
            'icon' => $this->icon,
            'dismisable' => $this->dismisable,
        ];
    }


}