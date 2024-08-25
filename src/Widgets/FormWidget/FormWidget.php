<?php

namespace LVP\Widgets\FormWidget;

use LVP\Enums\HttpMethod;
use LVP\Widgets\LVPWidget;

class FormWidget extends LVPWidget
{

    protected string $_widget_type = 'form';

    protected array $_fields = [];
    protected array $_formData = [];
    /**
     *  @var \LVP\Actions\Action[] 
     */
    protected array $_header_left_actions = [];
    /**
     * @var \LVP\Actions\Action[]
     */
    protected array $_header_right_actions = [];
    /**
     * @var \LVP\Actions\Action[]
     */
    protected array $_footer_left_actions = [];
    /**
     * @var \LVP\Actions\Action[]
     */
    protected array $_footer_right_actions = [];
    protected string $_title = '';
    protected string $_lvp_action = '';
    protected string $_action = '';
    protected string $_submit_label = 'Submit';
    protected string $_submit_btn_class = '';
    protected bool $_confirm_before_submit = false;
    protected bool $_is_card = true;
    protected bool $_is_headless = false;
    protected string $_confirmation_title = 'Confirmation';
    protected string $_confirmation_message = 'Are you sure?';
    protected string $_method = 'POST';

    protected array $_cols = [
        'all' => 3,
        'sm' => 1,
        'md' => 1,
        'lg' => 3,
    ];
    protected int $_gap = 3;

    public function __construct(string $title)
    {
        $this->_title = $title;
    }

    public static function make(string $title = '')
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
    public function headerLeftActions(array $actions)
    {
        $this->_header_left_actions = $actions;
        return $this;
    }
    public function headerRightActions(array $actions)
    {
        $this->_header_right_actions = $actions;
        return $this;
    }
    public function footerLeftActions(array $actions)
    {
        $this->_footer_left_actions = $actions;
        return $this;
    }
    public function footerRightActions(array $actions)
    {
        $this->_footer_right_actions = $actions;
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
    public function lvpAction(string $lvp_action)
    {
        $this->_lvp_action = $lvp_action;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['fields'] = $this->_fields;
        $data['title'] = $this->_title;
        $data['lvpAction'] = $this->_lvp_action;
        $data['action'] = $this->_action;
        $data['onSubmit'] = $this->_action;
        $data['method'] = $this->_method;
        $data['submitBtnLabel'] = $this->_submit_label;
        $data['submitBtnClass'] = $this->_submit_btn_class;
        $data['grid_cols'] = $this->_cols['all'];
        $data['grid_cols_'] = $this->_cols;
        $data['gap'] = $this->_gap;
        $data['fields'] = array_map(fn($it) => ($it->render($this)), ($this->_fields));
        $data['formData'] = $this->_formData;
        $data['confirmBeforeSubmit'] = $this->_confirm_before_submit;
        $data['confirmationTitle'] = $this->_confirmation_title;
        $data['confirmationMessage'] = $this->_confirmation_message;
        $data['isCard'] = $this->_is_card;
        $data['isHeadless'] = $this->_is_headless;
        $data['headerLeftActions'] = array_map(fn($it) => ($it->render()), ($this->_header_left_actions));
        $data['headerRightActions'] = array_map(fn($it) => ($it->render()), ($this->_header_right_actions));
        $data['footerLeftActions'] = array_map(fn($it) => ($it->render()), ($this->_footer_left_actions));
        $data['footerRightActions'] = array_map(fn($it) => ($it->render()), ($this->_footer_right_actions));

        return $data;
    }
}
