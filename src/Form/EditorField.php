<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsTextField;

class EditorField extends FormField
{
    use IsTextField;

    protected string $_component_path = 'text_editor';

    public function __construct()
    {
        $this->_type = 'editor';
    }

    protected function beforeRender(array $data): array
    {

        return $data;
    }
}
