<?php

namespace LVP\Actions;

class ButtonAction extends Action
{
    protected string $_action_type = 'button';
    protected string $_btn_class = 'lvp-button primary';

    public function btnClass($btn_css_class = 'lvp-button primary')
    {
        $this->_btn_class = $btn_css_class;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['btn_class'] = $this->_btn_class;
        return $data;
    }
}
