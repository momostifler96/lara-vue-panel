<?php

namespace LVP\Widgets\DataWidgets\Actions;

class DataActionMenu
{

    protected string $_type = 'inline';
    protected string $_label = 'Bulk actions';
    protected string $_icon = 'bulk';
    /**
     * Undocumented variable
     *
     * @var DataActionButton[]
     */
    protected array $_actions = [];


    public function dropdown()
    {
        $this->_type = 'dropdown';
        return $this;
    }
    /**
     * Define actions
     *
     * @param DataActionButton[] $actions
     */
    public function actions(array $actions)
    {
        $this->_actions = $actions;
        return $this;
    }
    public function type(string $type)
    {
        $this->_type = $type;
        return $this;
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


    public function render(): array
    {
        return [
            'type' => $this->_type,
            'label' => $this->_label,
            'icon' => $this->_icon,
            'actions' => array_map(function ($action) {
                return $action->render();
            }, $this->_actions),
        ];
    }
}
