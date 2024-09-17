<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class TextFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_name;
    protected string $_component = 'text';
    protected string $_mask = '';
    protected string $_label;
    protected string $_type = 'text';

    public function maxLength(int $max = 255)
    {
        $this->_rules = array_merge($this->_rules, ['max:' . $max]);
        return $this;
    }

    public function minLength(int $min = 0)
    {
        $this->_rules = array_merge($this->_rules, ['min:' . $min]);
        return $this;
    }

    public function type(string $type)
    {
        $this->_type = $type;
        if ($type == 'email') {
            $this->_rules = array_merge($this->_rules, ['email']);
        } else if ($type == 'phone') {
            $this->_rules = array_merge($this->_rules, ['regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im']);
        } else if ($type == 'url') {
            $this->_rules = array_merge($this->_rules, ['url']);
        } else if ($type == 'number') {
            $this->_rules = array_merge($this->_rules, ['numeric']);
        }
        return $this;
    }


    public function mask(string $mask)
    {
        $this->_mask = $mask;

        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['mask'] = $this->_mask;
        $data['type'] = $this->_type;
        return $data;
    }
}