<?php

namespace LVP\Actions;

class Action
{
    private string $_icon;
    private string $_icon_position = 'left';
    private string $_label;
    protected string $_action_type;

    public function __construct()
    {
    }

    public static function make()
    {
        return new static();
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    public function icon(string $icon)
    {
        $this->_icon = $icon;
        return $this;
    }
    public function iconPosition(string $position)
    {
        $this->_icon_position = $position;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        return $data;
    }
    public function render(): array
    {

        $data = [
            'icon' => empty($this->_icon) ? null : $this->_icon,
            'label' => empty($this->_label) ? null : $this->_label,
            'icon_position' => $this->_icon_position,
            'action_type' => $this->_action_type
        ];

        return $this->beforeRender($data);
    }
}
