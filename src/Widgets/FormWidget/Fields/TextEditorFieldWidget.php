<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class TextEditorFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_component = 'text-editor';
    protected array $_tools = [];

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


    protected function beforeRender(array $data): array
    {
        $data['label'] = $this->_label;
        $data['tools'] = $this->_tools;
        return $data;
    }
}