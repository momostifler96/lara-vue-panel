<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\FormWidget;


class FormFieldWidget
{
    /**
     * The name of the form field.
     * @var string
     */
    protected string $_name;

    /**
     * The component type of the form field.
     * @var string
     */
    protected string $_component;

    /**
     * The default value of the form field.
     * @var mixed
     */
    protected mixed $_default_value = null;

    /**
     * Indicates if the field is required.
     * @var bool
     */
    protected bool $_required = false;

    /**
     * The label of the form field.
     * @var string
     */
    protected string $_label = '';

    /**
     * The label of the form field.
     * @var bool
     */
    protected bool $_no_label = false;

    /**
     * The column span of the form field.
     * @var string
     */
    protected string $_colspan = '1';

    /**
     * The validation rules for the form field.
     * @var array
     */
    protected array $_rules = [];

    /**
     * Determines if the field can be filled on edit or create actions.
     * @var array
     */
    protected array $_fill_on = [
        'edit' => true,
        'create' => true
    ];

    /**
     * Determines if the field is disabled on edit or create actions.
     * @var array
     */
    protected array $_disabled_on = [
        'edit' => false,
        'create' => false
    ];

    /**
     * The field to load data from when editing.
     * @var string|null
     */
    protected string|null $_on_edit_field = null;

    /**
     * Determines if the field is hidden on edit or create actions.
     * @var array
     */
    protected array $_hidden_on = [
        'edit' => false,
        'create' => false
    ];

    /**
     * The field to load data from.
     * @var string
     */
    protected string $_load_data_from = '';

    /**
     * The events associated with the form field.
     * @var array
     */
    protected array $_events = [
        'change' => [],
        'clear' => [],
        'save' => [],
    ];

    /**
     * Constructor for the FormFieldWidget.
     * @param string $name The name of the form field.
     */
    public function __construct(string $name = '')
    {
        $this->_name = $name;
    }

    /**
     * Create a new instance of the FormFieldWidget.
     * @param string $name The name of the form field.
     * @return static
     */
    public static function make(string $name = '')
    {
        return new static($name);
    }

    /**
     * Set the label for the form field.
     * @param string $label The label to set.
     * @return $this
     */
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    /**
     * Set the label for the form field.
     * @return $this
     */
    public function noLabel()
    {
        $this->_no_label = true;
        return $this;
    }

    /**
     * Set the default value for the form field.
     * @param mixed $value The default value to set.
     * @return $this
     */
    public function value(mixed $value)
    {
        $this->_default_value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->_default_value;
    }

    /**
     * Set the column span for the form field.
     * @param mixed $value The column span to set.
     * @return $this
     */
    public function colSpan(mixed $value)
    {
        $this->_colspan = (string) $value;
        return $this;
    }

    /**
     * Set the field to load data from.
     * @param string $column The field to load data from.
     * @return $this
     */
    public function loadDataFrom($column)
    {
        $this->_load_data_from = $column;
        return $this;
    }



    /**
     * Set the field as required.
     * @param bool $required Whether the field is required.
     * @return $this
     */
    public function required(bool $required = true)
    {
        $this->_rules = array_merge($this->_rules, ['required']);
        $this->_required = $required;
        return $this;
    }

    /**
     * Method to be overridden for custom rendering logic.
     * @param array $data The data to be rendered.
     * @return array The modified data.
     */
    protected function beforeRender(array $data): array
    {
        return $data;
    }

    /**
     * Get the field name.
     * @return string The field name.
     */
    public function field()
    {
        return $this->_name;
    }

    /**
     * Add an onChange event listener.
     * @param mixed $action The action to perform.
     * @param mixed $fields The fields affected.
     * @param int $debounce The debounce time.
     * @return $this
     */
    public function onChange($action, $fields, $debounce = 0)
    {
        $this->_events['change'][] = compact('action', 'fields', 'debounce');
        return $this;
    }

    /**
     * Add an onSave event listener.
     * @param mixed $action The action to perform.
     * @param mixed $fields The fields affected.
     * @return $this
     */
    public function onSave($action, $fields)
    {
        $this->_events['clear'][] = compact('action', 'fields');
        return $this;
    }

    /**
     * Add an onClear event listener.
     * @param mixed $action The action to perform.
     * @param mixed $fields The fields affected.
     * @param int $debounce The debounce time.
     * @return $this
     */
    public function onClear($action, $fields, $debounce = 0)
    {
        $this->_events['clear'][] = compact('action', 'fields', 'debounce');
        return $this;
    }

    /**
     * Render the form field.
     * @param FormWidget|null $formWidget The form widget instance.
     * @return array The rendered form field data.
     */
    public function render()
    {

        $props = [
            'name' => $this->_name,
            'label' => !$this->_no_label ? !empty($this->_label) ? ucfirst($this->_label) : str($this->_name)->kebab()->replace('-', ' ')->ucfirst() : null,
            'rules' => $this->_rules,
            'value' => $this->_default_value,
            'required' => $this->_required,
            'colspan' => $this->_colspan,
        ];
        return [
            'name' => $this->_name,
            'type' => $this->_component,
            'eventsListeners' => $this->_events,
            'props' => $this->beforeRender($props)
        ];
    }

    public function onStoreData(&$formData, $request)
    {
        $formData[$this->_name] = $request[$this->_name];
    }
    public function onUpdateData(&$formData, $request, $oldData)
    {

        $data = $request->all()[$this->_name];
        $formData[$this->_name] = empty($data) ? $oldData[$this->_name] : $data;
    }

    /**
     * Get the validation rules for the field.
     * @param string $action The current action (create or edit).
     * @return array The validation rules.
     */
    public function getRules($action)
    {
        $rules = $this->_rules;
        if (!$this->canfillOn($action)) {
            unset($rules['required']);
            $rules = [];
        }

        return $rules;
    }

    /**
     * Set the field to load data from when editing.
     * @param string $fields The fields to load data from.
     * @return $this
     */
    public function onEditLoadData(string $fields)
    {
        $this->_on_edit_field = $fields;
        return $this;
    }

    /**
     * Get the field to load data from when editing.
     * @return string|null The field to load data from.
     */
    public function onEditData()
    {
        return $this->_on_edit_field;
    }

    /**
     * Set whether the field can be filled on create.
     * @param bool $fill Whether the field can be filled.
     * @return $this
     */
    public function emptyOnStore()
    {
        $this->_fill_on['create'] = false;
        return $this;
    }

    /**
     * Set whether the field can be filled on edit.
     * @param bool $fill Whether the field can be filled.
     * @return $this
     */
    public function emptyOnUpdate()
    {
        $this->_fill_on['edit'] = false;
        return $this;
    }

    /**
     * Set whether the field can be filled on both create and edit.
     * @param bool $fill Whether the field can be filled.
     * @return $this
     */
    public function emptyOnSave()
    {
        $this->_fill_on['edit'] = false;
        $this->_fill_on['create'] = false;
        return $this;
    }

    /**
     * Check if the field can be filled on create.
     * @param bool $fill Whether the field can be filled.
     * @return bool Whether the field can be filled.
     */
    public function canfillOnCreate(bool $fill = true)
    {
        return $this->_fill_on['create'] && !$this->_disabled_on['create'] && !$this->_hidden_on['create'];
    }

    /**
     * Check if the field can be filled on edit.
     * @param bool $fill Whether the field can be filled.
     * @return bool Whether the field can be filled.
     */
    public function canfillOnEdit(bool $fill = true)
    {
        return $this->_fill_on['edit'] && !$this->_disabled_on['edit'] && !$this->_hidden_on['edit'];
    }

    /**
     * Check if the field can be filled for a given action.
     * @param string $action The action to check (create or edit).
     * @param bool $fill Whether the field can be filled.
     * @return bool Whether the field can be filled.
     */
    public function canfillOn($action, bool $fill = true)
    {
        return $this->_fill_on[$action] && !$this->_disabled_on[$action] && !$this->_hidden_on[$action];
    }

    /**
     * Check if the field is hidden on create.
     * @return bool Whether the field is hidden.
     */
    public function isHiddenOnCreate()
    {
        return $this->_hidden_on['create'];
    }

    /**
     * Check if the field is hidden on edit.
     * @return bool Whether the field is hidden.
     */
    public function isHiddenOnEdit()
    {
        return $this->_hidden_on['edit'];
    }

    /**
     * Handle the field data on store.
     * @param mixed $field_data The field data.
     * @return string|array|null The processed field data.
     */
    public function onStore($field_data): string|array|null
    {
        return $field_data;
    }

    /**
     * Handle the field data on update.
     * @param mixed $field_data The new field data.
     * @param mixed $old_data The old field data.
     * @return string|array|null The processed field data.
     */
    public function onUpdate($field_data, $old_data): string|array|null
    {
        return $field_data;
    }

    /**
     * Load the field data.
     * @return array The field data.
     */
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