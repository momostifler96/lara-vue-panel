<?php

namespace LVP\Widgets;

class LVPWidget
{
    protected string $_widget_type = 'chart';
    protected string $_label = "";
    protected int $_col_span = 1;

    protected function beforeRender(array $data): array
    {
        return $data;
    }
    public function colSpan(int $col_span)
    {
        $this->_col_span = $col_span;

        return $this;
    }

    public function render()
    {
        $_render_data = [
            'widget_type' => $this->_widget_type,
            'label' => $this->_label,
            'col_span' => $this->_col_span,
        ];
        return $this->beforeRender($_render_data);
    }
}
