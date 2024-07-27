<?php

namespace LVP\Traits;

trait FormFieldHasCallback
{
  protected $_call_backs = [];

  public function handleCallback()
  {
    foreach ($this->_call_backs as $key => $fun) {
      $this->$key = $fun($this);
    }
  }
}