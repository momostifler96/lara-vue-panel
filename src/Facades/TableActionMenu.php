<?php

namespace LVP\Facades;

class TableActionMenu
{

    protected string $_type = 'inline';
    /**
     * Undocumented variable
     *
     * @var TableActionButton[]
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
     * @param TableActionButton[] $actions
     */
    public function actions(array $actions)
    {
        $this->_actions = $actions;
        return $this;
    }


    public function render(): array
    {
        return  [
            'type' => $this->_type,
            'actions' => array_map(function ($action) {
                return $action->render();
            }, $this->_actions),
        ];
    }
}
