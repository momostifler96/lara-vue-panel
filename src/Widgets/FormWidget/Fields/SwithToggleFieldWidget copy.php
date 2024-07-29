<?php

namespace LVP\Widgets\FormWidget\Fields;

class FormSectionWidget extends FormFieldWidget
{
    protected string $_component = 'form-section';
    protected bool $_dismisable = false;
    protected array $_fields = [];
    protected array $_cols = [
        'all' => 12,
        'sm' => 12,
        'md' => 6,
        'lg' => 6,
    ];
    protected int $_gap = 3;


    public function gap(int $gap = 3)
    {
        $this->_gap = $gap;
        return $this;
    }



    public function dismisable(bool $dismisable = true)
    {
        $this->_dismisable = $dismisable;
        return $this;
    }

    public function cols(int $cols = 3)
    {
        $this->_cols['all'] = $cols;
        return $this;
    }

    public function colsSm(int $cols = 3)
    {
        $this->_cols['sm'] = $cols;
        return $this;
    }

    public function colsMd(int $cols = 3)
    {
        $this->_cols['md'] = $cols;
        return $this;
    }

    public function colsLg(int $cols = 3)
    {
        $this->_cols['lg'] = $cols;
        return $this;
    }
    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }
    protected function beforeRender(array $data): array
    {
        $data['dismisable'] = $this->_dismisable;
        $data['cols'] = $this->_cols;
        $data['fields'] = array_map(fn($it) => ($it->render()), ($this->_fields));
        $data['gap'] = $this->_gap;
        return $data;
    }
}