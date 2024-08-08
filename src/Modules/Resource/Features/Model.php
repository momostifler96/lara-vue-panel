<?php

namespace LVP\Modules\Resource\Features;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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