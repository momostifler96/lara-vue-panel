<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsTextField;

class TextAreaField extends FormField
{
    use IsTextField;

    protected string $_component_path = 'textarea';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'textarea';
    }

    protected function beforeRender(array $data): array
    {

        return $data;
    }
}
