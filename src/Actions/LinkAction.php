<?php

namespace LVP\Actions;

class LinkAction extends Action
{
    protected string $_action_type = 'button';
    protected string $_btn_class = 'lvp-button primary';
    protected string $_link_url = '#';
    protected string $_target;

    public function btnClass($btn_css_class = 'lvp-button primary')
    {
        $this->_btn_class = $btn_css_class;
        return $this;
    }

    public function url(string $url)
    {
        $this->_link_url = $url;
        return $this;
    }
    public function target(string $target = '_blank')
    {
        $this->_target = $target;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['btn_class'] = $this->_btn_class;
        $data['link_url'] = $this->_link_url;
        $data['target'] = $this->_target;
        return $data;
    }
}
