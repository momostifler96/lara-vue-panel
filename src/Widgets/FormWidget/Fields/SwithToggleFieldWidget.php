<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Enums\DateFieldType;
use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class SwithToggleFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_component = 'toggle';
    protected mixed $_true_value = true;
    protected mixed $_false_value = false;


    public function trueValue($value)
    {
        $this->_true_value = $value;
        return $this;
    }

    public function falseValue($value)
    {
        $this->_false_value = $value;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['trueValue'] = $this->_true_value;
        $data['falseValue'] = $this->_false_value;
        return $data;
    }
}