<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class SectionWidget extends FormFieldWidget
{
    use HasPlaceholder;
    protected string $_name;
    protected string $_component = 'section';
    protected string $_label;
    protected int $_cols = 1;
    protected int $_gap = 3;
    protected int $_filled = 1;
    protected string $_sticky = '';
    protected array $_sections = [];
    protected array $_footer = [];
    protected array $_header = [];

    public function section(array $fiedls)
    {
        $this->_sections[] = $fiedls;
        return $this;
    }
    public function noCard()
    {
        $this->_filled = 0;
        return $this;
    }
    public function footerWidgets(array $footer)
    {
        $this->_footer = $footer;
        return $this;
    }
    public function headerWidgets(array $header)
    {
        $this->_header = $header;
        return $this;
    }
    public function sticky(string $sticky)
    {
        $this->_sticky = $sticky;
        return $this;
    }
    public function gap(int $gap)
    {
        $this->_gap = $gap;
        return $this;
    }
    public function cols(int $cols)
    {
        $this->_cols = $cols;
        return $this;
    }



    protected function beforeRender(array $data): array
    {
        $data['sections'] = array_map(function ($sec) {
            return array_map(function ($field) {
                return $field->render();
            }, $sec);
        }, $this->_sections);
        $data['gap'] = $this->_gap;
        $data['cols'] = $this->_cols;
        $data['filled'] = $this->_filled;
        $data['sticky'] = $this->_sticky;
        $data['footer'] = array_map(function ($w) {
            $w->render();
        }, $this->_footer);
        $data['header'] = array_map(function ($w) {
            $w->render();
        }, $this->_header);
        return $data;
    }
}