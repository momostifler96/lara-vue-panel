<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Enums\DateFieldType;
use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class DateFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_component = 'date-field';
    protected bool $_range;
    protected string $_max_date;
    protected string $_min_date;
    protected string $_type = 'date';

    public function type(DateFieldType $type)
    {
        $this->_type = $type->value;
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
        $this->_range = $value;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['type'] = $this->_type;
        $data['range'] = $this->_range;
        $data['minDate'] = $this->_min_date;
        $data['maxDate'] = $this->_max_date;
        return $data;
    }
}