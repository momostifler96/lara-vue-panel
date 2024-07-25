<?php

namespace LVP\Table;

use LVP\Facades\TableColumn;
use Illuminate\Support\Facades\Storage;

class ImageColumn extends TableColumn
{
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
}
