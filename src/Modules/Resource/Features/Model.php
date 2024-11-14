<?php

namespace LVP\Modules\Resource\Features;


trait Model
{

    public function boot()
    {
        $this->bootResource();
    }
    public function getModel()
    {
        return $this->model;
    }

}