<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsFileField;
use GuzzleHttp\Exception\InvalidArgumentException;

class FileUploadField extends FormField
{
    use IsFileField;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'file';
        $this->_rules = ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx'];
    }


    public function fileType(string $type = 'unknown')
    {
        $this->_file_type = $type;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $dt = [
            ...$data,
            'file_type' => empty($this->_file_type) ? null : $this->_file_type,
            'max_file_size' => empty($this->_max_file_size) ? null : $this->_max_file_size,
        ];
        return $dt;
    }


}
