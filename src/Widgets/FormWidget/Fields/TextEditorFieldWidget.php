<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class TextEditorFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_component = 'text-editor';
    protected array $_tools = [
        'undo',
        'redo',
        'text-types',
        'bold',
        'divider',
        'italic',
        'code',
        'text-align',
        'hightline',
        'strike',
        'underline',
        'color',
        'link',
        'image'
    ];

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
    public function tools(array $tools)
    {
        $this->_tools = $tools;
        return $this;
    }


    protected function beforeRender(array $data): array
    {
        $data['label'] = $this->_label;
        $data['tools'] = $this->_tools;
        return $data;
    }
}