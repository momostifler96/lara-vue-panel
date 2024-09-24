<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Facades\TableColumn;

class ToggleColumn extends TableColumn
{

    protected $_true_value = '1';
    protected $_false_value = '0';
    protected $_action = 'update_col';

    protected bool $_has_confirmation = false;

    protected string $_confirmation_title;
    protected string $_confirmation_body;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'toggle';
        $this->_editable = true;
    }

    public function setTrueValue(string $value)
    {
        $this->_true_value = $value;
        return $this;
    }
    public function action(string $action)
    {
        $this->_action = $action;
        return $this;
    }
    public function setFalseValue(string $value)
    {
        $this->_false_value = $value;
        return $this;
    }

    public function setConfirmationTitle(string $title)
    {
        $this->_confirmation_title = $title;
        return $this;
    }
    public function setConfirmationBody(string $body)
    {
        $this->_confirmation_body = $body;
        return $this;
    }
    public function hasConfirmation()
    {
        $this->_has_confirmation = true;
        return $this;
    }

    public function beforeRender(array $data)
    {
        $data['action'] = $this->_action;
        $data['true_value'] = $this->_true_value;
        $data['false_value'] = $this->_false_value;
        $data['has_confirmation'] = $this->_has_confirmation;
        if (!empty($this->_confirmation_title)) {
            $data['confirmation_title'] = $this->_confirmation_title;
        }
        if (!empty($this->_confirmation_body)) {
            $data['confirmation_body'] = $this->_confirmation_body;
        }
        return $data;
    }
}
