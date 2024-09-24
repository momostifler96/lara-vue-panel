<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Facades\TableColumn;

class ActionColumn extends TableColumn
{

    protected string $_type = 'group';
    protected array $_actions = [];
    protected string $_action_type = 'inline';
    protected string $align_items = 'center';

    public function actions(array $actions)
    {
        $this->_actions[] = $actions;
        return $this;
    }
    public function isDropDownMenu()
    {
        $this->_action_type = 'dropdown';
        return $this;
    }

    public function beforeRender(array $data)
    {
        $data['action_type'] = $this->_action_type;
        $data['actions'] = array_map(fn($it) => array_map(fn($its) => ($its->render()), $it), $this->_actions);
        $data['align'] = 'right';
        // 'type' => 'actions',
        // 'label' => '',
        // 'field' => 'actions',
        // 'align' => 'right',
        // 'data' => $this->getTableActions(),
        return $data;
    }

}
