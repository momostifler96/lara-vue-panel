<?php
namespace LVP\Support;


class InfoContainer
{
    protected string $_type;

    protected function onRender(array $data)
    {
        return $data;
    }
    public static function make()
    {
        return new static();
    }

    public function render(array &$data = [])
    {
        return [
            'type' => $this->_type,
            'props' => $this->onRender($data),
        ];
    }
}