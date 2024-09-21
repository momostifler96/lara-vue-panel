<?php

namespace LVP\Actions;
use LVP\Enums\HttpMethod;
use LVP\Widgets\FormWidget\FormWidget;

class ActionForm extends Action
{
    protected string $_action_type = 'form';
    protected string $_btn_class = 'lvp-button primary';
    protected string $_target = '';
    protected array $_fields = [];
    protected string $_submit_label = 'Submit';
    protected string $_cancel_label = 'Cancel';
    protected string $_submit_url = '';
    protected string $_action = '';
    protected int $_grid_cols = 1;
    protected int $_gap = 1;
    protected bool $_confirm_before_submit = false;
    protected HttpMethod $_method = HttpMethod::POST;

    public function btnClass($btn_css_class = 'lvp-button primary')
    {
        $this->_btn_class = $btn_css_class;
        return $this;
    }

    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }
    public function gap(int $gap)
    {
        $this->_gap = $gap;
        return $this;
    }
    public function gridCols(int $gridCols)
    {
        $this->_grid_cols = $gridCols;
        return $this;
    }

    public function submitLabel(string $submit_label)
    {
        $this->_submit_label = $submit_label;
        return $this;
    }
    public function submitUrl(string $url)
    {
        $this->_submit_url = $url;
        return $this;
    }
    public function cancelLabel(string $cancel_label)
    {
        $this->_cancel_label = $cancel_label;
        return $this;
    }
    public function action(string $action)
    {
        $this->_action = $action;
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
        $data['target'] = $this->_target;
        $data['form'] = FormWidget::make('form')
            ->fields($this->_fields)
            ->submitBtnLabel($this->_submit_label)
            ->cancelBtnLabel($this->_cancel_label)
            ->method($this->_method)
            ->cols($this->_grid_cols)
            ->gap($this->_gap)
            ->action($this->_action)
            ->submitUrl($this->_submit_url)
            ->confirmBeforeSubmit($this->_confirm_before_submit)->render();
        return $data;
    }
}
