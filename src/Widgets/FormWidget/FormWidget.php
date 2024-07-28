<?php

namespace LVP\Widgets\FormWidget;

use LVP\Widgets\LVPWidget;

class FormWidget extends LVPWidget
{


    protected array $_fields = [];
    protected string $_title = '';
    protected string $_action = '';
    protected string $_submit_label = '';

    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }
    public function submitLabel(string $label)
    {
        $this->_submit_label = $label;
        return $this;
    }
    public function action(string $action)
    {
        $this->_action = $action;
        return $this;
    }
    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['fields'] = $this->_fields;
        $data['title'] = $this->_title;
        $data['action'] = $this->_action;
        $data['submit_label'] = $this->_submit_label;
        return $data;
    }
}
