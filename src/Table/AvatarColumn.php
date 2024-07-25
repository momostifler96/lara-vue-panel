<?php

namespace LVP\Table;

use LVP\Facades\TableColumn;

class AvatarColumn extends TableColumn
{
    protected string $_rounded = 'rounded-full';
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'avatar';
        $this->_editable = false;
    }



    public function rounded(bool $value = true)
    {
        $this->_rounded = $value ? 'rounded-full' : 'rounded';
        return $this;
    }
}
