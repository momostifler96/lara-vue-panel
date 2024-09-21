<?php

namespace LVP\Actions;

class ActionButton extends Action
{
    private string $_action;

    protected string $_action_type = 'button';
    protected string $_btn_class = 'lvp-button primary';

    public function btnClass($btn_css_class = 'lvp-button primary')
    {
        $this->_btn_class = $btn_css_class;
        return $this;
    }

    public function action(string $action)
    {
        $this->_action = $action;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['btn_class'] = $this->_btn_class;
        $data['action'] = $this->_action;
        return $data;
    }
}
