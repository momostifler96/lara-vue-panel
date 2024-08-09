<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\FormWidget;


class FormFieldWidget
{
    protected string $_name;
    protected string $_component;
    protected mixed $_default_value = null;
    protected bool $_required = false;
    protected string $_label = '';
    protected string $_colspan = '1';
    protected array $_rules = [];
    protected array $_fill_on = [
        'edit' => true,
        'create' => true
    ];
    protected array $_disabled_on = [
        'edit' => false,
        'create' => false
    ];
    protected string|null $_on_edit_field = null;
    protected array $_hidden_on = [
        'edit' => false,
        'create' => false
    ];
    protected string $_load_data_from = '';
    protected array $_events = [
        'change' => [],
        'clear' => [],
        'save' => [],
    ];
    public function __construct(string $name)
    {
        $this->_name = $name;
    }

    public static function make(string $name)
    {
        return new static($name);
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    public function value(mixed $value)
    {
        $this->_default_value = $value;
        return $this;
    }
    public function colSpan(mixed $value)
    {
        $this->_colspan = (string) $value;
        return $this;
    }
    public function loadDataFrom($column)
    {
        $this->_load_data_from = $column;
        return $this;
    }
    public function getValue(FormWidget $formWidget)
    {
        $formWidget->setFormData($this->_name, $this->_default_value);
    }
    public function required(bool $required = true)
    {
        $this->_rules = array_merge($this->_rules, ['required']);
        $this->_required = $required;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        return $data;
    }
    public function field()
    {
        return $this->_name;
    }
    public function onChange($action, $fields, $debounce = 0)
    {
        $this->_events['change'][] = compact('action', 'fields', 'debounce');
        return $this;
    }
    public function onSave($action, $fields)
    {
        $this->_events['clear'][] = compact('action', 'fields');
        return $this;
    }
    public function onClear($action, $fields, $debounce = 0)
    {
        $this->_events['clear'][] = compact('action', 'fields', 'debounce');
        return $this;
    }
    public function render(FormWidget|null $formWidget = null)
    {
        if ($formWidget) {
            $this->getValue($formWidget);
        }
        $props = [
            'name' => $this->_name,
            'label' => $this->_label ?? $this->_label = str($this->_name)->kebab()->replace('-', ' ')->ucfirst(),
            'value' => $this->_default_value,
            'rules' => $this->_rules,
            'required' => $this->_required,
            'colspan' => $this->_colspan,
        ];
        return [
            'name' => $this->_name,
            'component' => $this->_component,
            'eventsListeners' => $this->_events,
            'props' => $this->beforeRender($props)
        ];
    }
    public function getRules($action)
    {
        $rules = $this->_rules;
        if (!$this->canfillOn($action)) {
            unset($rules['required']);
            $rules = [];
        }

        return $rules;
    }
    public function onEditLoadData(string $fields)
    {
        $this->_on_edit_field = $fields;
        return $this;
    }
    public function onEditData()
    {
        return $this->_on_edit_field;
    }
    public function fillOnCreate(bool $fill = true)
    {
        $this->_fill_on['create'] = $fill;
        return $this;
    }
    public function fillOnEdit(bool $fill = true)
    {
        $this->_fill_on['edit'] = $fill;
        return $this;
    }
    public function fill(bool $fill = true)
    {
        $this->_fill_on['edit'] = $fill;
        $this->_fill_on['create'] = $fill;
        return $this;
    }

    public function canfillOnCreate(bool $fill = true)
    {
        return $this->_fill_on['create'] && !$this->_disabled_on['create'] && !$this->_hidden_on['create'];
    }
    public function canfillOnEdit(bool $fill = true)
    {
        return $this->_fill_on['edit'] && !$this->_disabled_on['edit'] && !$this->_hidden_on['edit'];
    }

    public function canfillOn($action, bool $fill = true)
    {
        return $this->_fill_on[$action] && !$this->_disabled_on[$action] && !$this->_hidden_on[$action];
    }

    public function isHiddenOnCreate()
    {
        return $this->_hidden_on['create'];
    }
    public function isHiddenOnEdit()
    {
        return $this->_hidden_on['edit'];
    }
    public function onStore($field_data): string|array|null
    {
        return $field_data;
    }

    public function onUpdate($field_data, $old_data): string|array|null
    {
        return $field_data;
    }
    public function load()
    {
        $props = [
            'field' => $this->_name,
            'load_data_from' => $this->_on_edit_field,
            'rules' => $this->_rules,
        ];
        return $props;
    }
}