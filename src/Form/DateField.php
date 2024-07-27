<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Enums\DateFieldType;
use LVP\Traits\IsTextField;

class DateField extends FormField
{
    use IsTextField;

    protected string $_component_path = 'date-field';
    protected string $_date_type = 'date';

    protected $_max_date = null;
    protected $_min_date = null;
    protected bool $_is_range = false;


    public function __construct()
    {
        $this->_type = 'date-field';
    }

    public function type(DateFieldType $type)
    {
        $this->_date_type = $type->value;
        return $this;

    }
    public function maxDate($date)
    {
        $this->_max_date = $date;
        return $this;
    }
    public function minDate($date)
    {
        $this->_min_date = $date;
        return $this;
    }


    public function isRange(bool $value = true)
    {
        $this->_is_range = $value;
        return $this;

    }

    protected function beforeRender(array $field_data): array
    {
        $field_data['type'] = $this->_date_type;
        $field_data['range'] = $this->_is_range;
        $field_data['minDate'] = $this->_min_date;
        $field_data['maxDate'] = $this->_max_date;

        return $field_data;
    }


}
