<?php
namespace LVP\Support;

class PanelNavLink
{
    protected $name;
    protected $path;
    protected $icon;
    protected $position;
    protected $group;
    public function __construct(string $name, string $path, string $icon = 'stack', int $position = 0, string|null $group)
    {
        $this->name = $name;
        $this->path = $path;
        $this->icon = $icon;
        $this->position = $position;
        $this->group = $group;
    }
    public static function make(string $name, string $path, string $icon = 'stack', int $position = 0, string|null $group = null)
    {
        return new self($name, $path, $icon, $position, $group);
    }

    public function getNavMenu()
    {
        return [
            'label' => $this->name,
            'position' => $this->position,
            'icon' => $this->icon,
            'path' => url($this->path),
        ];
    }

    public function getMenuGroup(): null|string
    {
        return $this->group ?? null;
    }
}