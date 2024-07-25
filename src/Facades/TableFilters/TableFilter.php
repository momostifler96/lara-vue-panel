<?php

namespace LVP\Facades\TableFilters;

class TableFilter
{

    protected array $_filters = [];
    protected array $_checkboxs = [];
    protected array $_dropdowns = [];
    protected array $_texts = [];
    protected string $_label = 'Filtres';
    protected string $_icon = 'lvp-filter-outline';
    protected string $_style = 'popover';
    protected string $_title = 'Filter';
    protected string $_value;
    protected bool $_show_reset = false;
    protected string $_reset_button_label = 'Reset all';


    public static function make()
    {
        return new static();
    }

    public function style(string $style = 'menu')
    {
        $this->_style = $style;
        return $this;
    }
    public function showReset(bool $show = true)
    {
        $this->_show_reset = $show;
        return $this;
    }
    public function resetButtonLabel(string $label)
    {
        $this->_reset_button_label = $label;
    }

    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }
    /**
     * Summary of checkboxs
     * @param TableFilterCheckbox[] $checkboxs
     * @return static
     */
    public function checkboxs(array $checkboxs)
    {
        $this->_checkboxs = $checkboxs;
        return $this;
    }
    /**
     * Summary of dropdowns
     * @param TableFilterCheckbox[] $dropdowns
     * @return static
     */
    public function dropdowns(array $dropdowns)
    {
        $this->_dropdowns = $dropdowns;
        return $this;
    }
    /**
     * Summary of texts
     * @param TableFilterText[] $texts
     * @return static
     */
    public function texts(array $texts)
    {
        $this->_texts = $texts;
        return $this;
    }



    public function render()
    {
        return [
            'filters' => [
                'checkboxs' => $this->_checkboxs,
                'dropdowns' => $this->_dropdowns,
                'texts' => $this->_texts,
            ],
            'icon' => $this->_icon,
            'style' => $this->_style,
            'show_reset' => $this->_show_reset,
            'reset_button_label' => $this->_reset_button_label,
        ];
    }
}
