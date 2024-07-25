<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsFileField;

class ImageUploadField extends FormField
{
    use IsFileField;
    protected string $_component_path = 'image_uploader';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'file';
        $this->_file_type = 'image';
        $this->_rules = ['image', 'mimes:jpg,jpeg,png,webp,svg'];
    }

    public function ratio(string $ratio = '1:1')
    {
        $this->_image_ratio = $ratio;
        return $this;
    }
    public function squareRatio()
    {
        $this->_image_ratio = '1:1';
        return $this;
    }

    public function onStore($field_data): string|array
    {
        if (!empty($field_data)) {
            if (!empty($this->_multiple)) {
                $_urls = [];
                foreach ($field_data as $key => $file) {
                    $file_name = time() . '-' . $file->getClientOriginalName();
                    $file_path = $file->storeAs('', $file_name, config('lvp.default_files_disk'));
                    $_urls[] = $file_path;
                }
                return $_urls;
            } else {
                $file = $field_data[0];
                $file_name = time() . '-' . $file->getClientOriginalName();
                $file_path = $file->storeAs('', $file_name, config('lvp.default_files_disk'));
                return $file_path;
            }
        }
        return '';
    }
    public function onUpdate($field_data, $old_data): string|array
    {
        return $this->onStore($field_data);
    }
}
