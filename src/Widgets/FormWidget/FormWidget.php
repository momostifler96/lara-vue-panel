<?php

namespace LVP\Widgets\FormWidget;

use LVP\Enums\HttpMethod;
use LVP\Widgets\LVPWidget;

class FormWidget extends LVPWidget
{


    protected string $_widget_type = 'form';

    protected array $_fields = [];
    protected array $_formData = [];
    protected string $_title = '';
    protected string $_action = '';
    protected string $_submit_label = '';
    protected string $_submit_btn_class = '';
    protected bool $_confirm_before_submit = false;
    protected bool $_is_card = true;
    protected string $_confirmation_title = 'Confirmation';
    protected string $_confirmation_message = 'Are you sure?';
    protected string $_method = 'POST';

    protected array $_cols = [
        'all' => 3,
        'sm' => 1,
        'md' => 1,
        'lg' => 3,
    ];
    protected int $_gap = 10;

    public function __construct(string $title)
    {
        $this->_title = $title;
    }

    public static function make(string $title)
    {
        return new static($title);
    }
    public function gap(int $gap = 3)
    {
        $this->_gap = $gap;
        return $this;
    }

    public function cols(int $cols = 3)
    {
        $this->_cols['all'] = $cols;
        return $this;
    }

    public function colsSm(int $cols = 3)
    {
        $this->_cols['sm'] = $cols;
        return $this;
    }

    public function colsMd(int $cols = 3)
    {
        $this->_cols['md'] = $cols;
        return $this;
    }

    public function colsLg(int $cols = 3)
    {
        $this->_cols['lg'] = $cols;
        return $this;
    }
    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }
    public function submitBtnLabel(string $label)
    {
        $this->_submit_label = $label;
        return $this;
    }

    public function submitBtnClass(string $class)
    {
        $this->_submit_btn_class = $class;
        return $this;
    }

    public function confirmBeforeSubmit(bool $confirm = true)
    {
        $this->_confirm_before_submit = $confirm;
        return $this;
    }

    public function confirmationTitle(string $title)
    {
        $this->_confirmation_title = $title;
        return $this;
    }
    public function confirmationMessage(string $message)
    {
        $this->_confirmation_message = $message;
        return $this;
    }
    public function action(string $action)
    {
        $this->_action = $action;
        return $this;
    }

    public function method(HttpMethod $method)
    {
        $this->_method = $method->value;
        return $this;
    }
    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }
    public function isCard(bool $isCard)
    {
        $this->_is_card = $isCard;
        return $this;
    }
    public function setFormData(string $name, mixed $data)
    {
        $this->_formData[$name] = $data;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['fields'] = $this->_fields;
        $data['title'] = $this->_title;
        $data['action'] = $this->_action;
        $data['onSubmit'] = $this->_action;
        $data['method'] = $this->_method;
        $data['submitBtnLabel'] = $this->_submit_label;
        $data['submitBtnClass'] = $this->_submit_btn_class;
        $data['cols'] = $this->_cols;
        $data['gap'] = $this->_gap;
        $data['fields'] = array_map(fn($it) => ($it->render($this)), ($this->_fields));
        $data['formData'] = $this->_formData;
        $data['confirmBeforeSubmit'] = $this->_confirm_before_submit;
        $data['confirmationTitle'] = $this->_confirmation_title;
        $data['confirmationMessage'] = $this->_confirmation_message;
        $data['isCard'] = $this->_is_card;

        return $data;
    }
}
