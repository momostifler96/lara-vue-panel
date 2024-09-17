<?php
namespace LVP\Utils;
use Illuminate\Http\Request;
class CreateLVPAction
{
    protected $name;
    protected $action;
    public $on_success_message;
    public $on_failed_message;


    public function __construct(string $name, callable $action, string $on_success_message = '', string $on_failed_message = '')
    {
        $this->name = $name;
        $this->action = $action;
        $this->on_success_message = $on_success_message;
        $this->on_failed_message = $on_failed_message;
    }

    public static function make(string $name, callable $action, string $on_success_message = '', string $on_failed_message = '')
    {
        $action = new static($name, $action, $on_success_message, $on_failed_message);
        return $action;
    }
    public function render()
    {
        return [
            'action' => $this->action,
            'on_success_message' => $this->on_success_message,
            'on_failed_message' => $this->on_failed_message,
        ];
    }

    public function getName()
    {
        return $this->name;
    }
    public function exec(string $model_class, Request $request)
    {
        $action = $this->action;
        $action($model_class, $request);
        // return call_user_func_array($this->action, [$model_class, $request]);
    }
}