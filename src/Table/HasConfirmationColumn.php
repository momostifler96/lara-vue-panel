<?php

namespace LVP\Table;

trait HasConfirmationColumn
{
    protected bool $_has_confirmation = false;

    protected string $_confirmation_title;
    protected string $_confirmation_body;

    public function confirmationTitle(string $title)
    {
        $this->_confirmation_title = $title;
        return $this;
    }
    public function confirmationBody(string $body)
    {
        $this->_confirmation_body = $body;
        return $this;
    }
    public function hasConfirmation()
    {
        $this->_has_confirmation = true;
        return $this;
    }
}
