<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use Illuminate\Support\Facades\Storage;
use LVP\Facades\TableColumn;

class AvatarColumn extends TableColumn
{
    protected string $_file_path = '/';
    protected string $_default = 'https://avatar.iran.liara.run/public/11';
    protected string $_rounded = 'rounded-full';
    protected int $_size = 20;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'avatar';
        $this->_editable = false;
    }

    public function disk($disk = 'public')
    {
        $this->_file_path = Storage::disk($disk)->url('');
        return $this;
    }

    public function rounded(bool $value = true)
    {
        $this->_rounded = $value ? 'rounded-full' : 'rounded';
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
