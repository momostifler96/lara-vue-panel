<?php
namespace LVP\Support;


class Info
{

    protected string $_label;
    protected string $_field;
    protected string $_type;


    public function __construct(string $field)
    {
        $this->_field = $field;
    }


    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }


    protected function onRender(array $data)
    {
        return $data;
    }
    public function field()
    {
        return $this->_field;
    }

    public static function make(string $field)
    {
        return new static($field);
    }


    public function render(array $data = [])
    {
        $data = [
            'label' => empty($this->_label) ? str($this->_field)->replace('_', ' ')->replace('-', ' ')->ucfirst() : $this->_label,
            'field' => $this->_field,
            'value' => @$data[$this->_field],
        ];

        return [
            'props' => $this->onRender($data),
            'type' => $this->_type,
        ];
    }
}