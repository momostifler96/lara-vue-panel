<?php

namespace LVP\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FormField
{

    protected string $_field;
    protected string $_label;
    protected string $_component_path;
    protected string $_type;
    protected bool $_autofocus = false;
    protected bool $_filter = false;
    protected array $_ajax_call_backs;

    protected string $_placeholder = '';
    protected string $_icon;
    protected string $_iconPosition;
    protected bool $_showLabel = true;
    protected bool $_helpText;
    protected bool $_showHelpText = false;
    protected bool $_showError = true;
    protected array $_fill_on = [
        'create' => true,
        'edit' => true,
    ];
    protected array $_hidden_on = [
        'create' => false,
        'edit' => false,
    ];
    protected array $_disabled_on = [
        'create' => false,
        'edit' => false,
    ];
    protected array $_readonly_on = [
        'create' => false,
        'edit' => false,
    ];
    protected array $_required_on = [
        'create' => true,
        'edit' => true,
    ];
    protected bool $_multiple;
    protected $_default = null;
    protected $_on_edit_field = null;
    protected string $_colspan = '1';
    protected string $_file_type = 'unknown';
    protected string $_max_file_size = '5M';
    protected string $_image_ratio = '1:1';
    protected array $_events = [
        'change' => [],
        'clear' => [],
        'save' => [],
    ];
    protected array $_image_responsive = [
        'sm' => '200px:200px',
        'md' => '400px:400px',
        'lg' => '800px:800px',
    ];
    protected array $_watch = [];
    protected array $_watch_save = [];
    protected array $_rules = [];
    protected array $_options = [];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public static function make($field)
    {
        $instance = new static();
        $instance->_field = $field;
        $instance->_label = str($field)->kebab()->replace('-', ' ')->camel()->ucfirst();
        return $instance;
    }
    public function onChange($action, $fields)
    {
        $this->_events['change'][] = ['action' => $action, 'fields' => $fields];
        return $this;
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
    public function onSave($action, $fields)
    {
        $this->_events['save'][] = ['action' => $action, 'fields' => $fields];
        return $this;
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
    public function hiddenOnEdit(bool $hidden = true)
    {
        $this->_hidden_on['edit'] = $hidden;

        return $this;
    }
    public function disabledOnCreate(bool $disabled = true)
    {
        $this->_disabled_on['create'] = $disabled;

        return $this;
    }
    public function disabledOnEdit(bool $disabled = true)
    {
        $this->_disabled_on['edit'] = $disabled;
        return $this;
    }
    public function readonlyOnCreate(bool $readonly = true)
    {
        $this->_readonly_on['create'] = true;

        return $this;
    }
    public function readonlyOnEdit(bool $readonly = true)
    {
        $this->_readonly_on['edit'] = true;
        return $this;
    }
    public function requiredOnCreate(bool $required = true)
    {
        $this->_required_on['create'] = $required;
        return $this;
    }
    public function requiredOnEdit(bool $required = true)
    {
        $this->_required_on['edit'] = $required;
        return $this;
    }
    public function onClear($action, $fields)
    {
        $this->_events['clear'][] = ['action' => $action, 'fields' => $fields];
        return $this;
    }

    public function label(string $value)
    {
        $this->_label = $value;
        return $this;
    }
    public function updateFieldsOnSave(string $value)
    {
        $this->_label = $value;
        return $this;
    }
    public function updateFieldsOnChange(array $value)
    {
        $this->_watch = $value;
        return $this;
    }


    public function autofocus(bool $value = true)
    {

        $this->_autofocus = $value;
        return $this;
    }
    public function showLabel(bool $value = true)
    {

        $this->_showLabel = $value;
        return $this;
    }
    public function showError(bool $value = true)
    {

        $this->_showError = $value;
        return $this;
    }
    public function helperText(string $value)
    {

        $this->_helpText = $value;
        return $this;
    }
    public function colSpan(string $value)
    {

        $this->_colspan = $value;
        return $this;
    }
    public function colSpanFull()
    {

        $this->_colspan = 'full';
        return $this;
    }

    //#rules

    public function getRules($action)
    {
        $rules = $this->_rules;
        if (!$this->canfillOn($action)) {
            unset($rules['required']);
            $rules = [];
        }

        return $rules;
    }
    public function getForm()
    {
        return [
            $this->_field => [
                'label' => $this->_label,
                'type' => $this->_type,
                'autofocus' => $this->_autofocus,
                'placeholder' => $this->_placeholder,
                'icon' => $this->_icon,
                'iconPosition' => $this->_iconPosition,
            ]
        ];
    }
    public function getFieldType()
    {
        return $this->_type;
    }
    public function isDisableOnCreate()
    {
        return $this->_disabled_on['create'];
    }
    public function isDisableOnEdit()
    {
        return $this->_disabled_on['edit'];
    }

    public function required(bool $value = true)
    {
        $this->_rules = array_merge($this->_rules, ['required']);
        return $this;
    }
    public function unique(string $table, $model_primary_key = null)
    {
        //'unique:table,column,except,id'

        $this->_rules = array_merge($this->_rules, ['unique:' . $table . empty($model_primary_key) ? '' : (',' . $this->_field . ',except,' . $model_primary_key)]);
        return $this;
    }
    public function default($value = null)
    {
        $this->_default = $value;
        return $this;
    }
    public function field()
    {
        return $this->_field;
    }
    public function defaultValue()
    {
        return $this->_default;
    }
    public function setupAjaxCallback()
    {
        if (!empty($this->_ajax_call_backs)) {
            Route::get('/lvp-api-call/field' . '/' . $this->_field, function (Request $request) {
                $data = $this->_ajax_call_backs[0]($request);
                return response()->json(compact('data'));
            });
        }
    }
    public function backRender()
    {
        return [
            'field' => $this->_field,
            'type' => $this->_type,
            'fill_on' => $this->_fill_on,
            'required_on' => $this->_required_on,
            'rules' => empty($this->_rules) ? null : $this->_rules,
            'file_type' => empty($this->_file_type) ? null : $this->_file_type,
            'max_file_size' => empty($this->_max_file_size) ? null : $this->_max_file_size,
            'image_ratio' => empty($this->_image_ratio) ? null : $this->_image_ratio,
            'image_responsive' => empty($this->_image_responsive) ? null : $this->_image_responsive,
            'options' => empty($this->_options) ? null : $this->_options,
        ];
    }
    public function onStore($field_data): string|array|null
    {
        return $field_data;
    }
    public function onUpdate($field_data, $old_data): string|array|null
    {
        if ($this->_field == 'password') {
            // dd($field_data, $old_data);
        }
        return $field_data;
    }



    protected function beforeRender(array $field_data): array
    {
        return $field_data;
    }

    public function render()
    {


        $data = [
            'component_path' => $this->_component_path,
            'field' => $this->_field,
            'label' => $this->_label,
            'type' => $this->_type,
            'multiple' => empty($this->_multiple) ? false : $this->_multiple,
            'events_listeners' => $this->_events,
            'input_regex' => $this->_events,
            'autofocus' => $this->_autofocus,
            'placeholder' => $this->_placeholder,
            'ajax_call' => !empty($this->_ajax_call_backs) ? '/lvp-api-call/field' . '/' . $this->_field : null,
            'icon' => empty($this->_icon) ? null : $this->_icon,
            'iconPosition' => empty($this->_iconPosition) ? null : $this->_iconPosition,
            'showLabel' => empty($this->_showLabel) ? null : $this->_showLabel,
            'helpText' => empty($this->_helpText) ? null : $this->_helpText,
            'showHelpText' => empty($this->_showHelpText) ? null : $this->_showHelpText,
            'showError' => empty($this->_showError) ? null : $this->_showError,
            'hidden_on' => $this->_hidden_on,
            'readonly_on' => $this->_readonly_on,
            'disabled_on' => $this->_disabled_on,
            'required_on' => $this->_required_on,
            'filter' => $this->_filter,
            'colspan' => empty($this->_colspan) ? null : $this->_colspan,
            'rules' => $this->_rules,
            'file_type' => empty($this->_file_type) ? null : $this->_file_type,
            'max_file_size' => empty($this->_max_file_size) ? null : $this->_max_file_size,
            'image_ratio' => empty($this->_image_ratio) ? null : $this->_image_ratio,
            'image_responsive' => empty($this->_image_responsive) ? null : $this->_image_responsive,
            'options' => empty($this->_options) ? null : $this->_options,
        ];
        return $this->beforeRender($data);

    }

}
