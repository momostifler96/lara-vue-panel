<?php

namespace LVP\Form;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LVP\Facades\FormField;
use LVP\Traits\FormFieldHasCallback;

class SelectField extends FormField
{
    use FormFieldHasCallback;
    protected string $_component_path = 'select-field';
    protected bool $_filter = false;
    protected $_ajax_call_backs;
    protected array $_options = [];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'select-field';
    }


    public function options(array|callable $value)
    {

        if (is_callable($value)) {
            $value = $value();
        }
        $this->_options = $value;
        return $this;
    }
    public function multiple(bool $multiple = true)
    {
        $this->_multiple = $multiple;
        return $this;
    }
    public function filter()
    {
        $this->_filter = true;
        return $this;
    }
    public function ajaxQuery(callable $func)
    {
        $this->_ajax_call_backs = $func;

        return $this;
    }

    public function setupAjaxCallback()
    {
        if (!empty($this->_ajax_call_backs)) {
            Route::get('/lvp-api-call/field' . '/' . $this->_field, function (Request $request) {
                $data = $this->_ajax_call_backs($request);
                return response()->json(compact('data'));
            });
        }
    }

    protected function beforeRender(array $data): array
    {
        $dt = [
            ...$data,
            'ajaxCall' => !empty($this->_ajax_call_backs) ? '/lvp-api-call/field' . '/' . $this->_field : null,
            'options' => empty($this->_options) ? null : $this->_options,
            'filter' => $this->_filter,
            'placeholder' => @$this->_placeholder,
            'multiple' => empty($this->_multiple) ? false : $this->_multiple,

        ];
        return $dt;
    }
}
