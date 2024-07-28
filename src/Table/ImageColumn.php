<?php

namespace LVP\Table;

use LVP\Facades\TableColumn;
use Illuminate\Support\Facades\Storage;

class ImageColumn extends TableColumn
{
    protected string $_file_path = '/';
    protected string $_default = '/';
    protected int $_size = 50;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'image';
        $this->_editable = false;
        $this->disk('public');
    }

    public function disk($disk = 'public')
    {
        $this->_file_path = Storage::disk($disk)->url('');
        return $this;
    }
    public function default(string $url)
    {
        $this->_default = $url;
        return $this;
    }
    public function size(int $size)
    {
        $this->_size = $size;
        return $this;
    }
    public function beforeRender(array $data)
    {
        $data['path'] = $this->_file_path;
        $data['default'] = $this->_default;
        $data['size'] = $this->_size;
        return $data;
    }
}
