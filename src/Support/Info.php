<?php
namespace LVP\Support;
use Carbon\Carbon;


class Info
{

    protected string $_label;
    protected string $_field;
    protected string $_type;
    protected string $_load_data_from;
    protected string $_date_format = '';
    protected array $_data_source = [];
    protected bool $_hide_label = false;


    public function __construct(string $field)
    {
        $this->_field = $field;
    }


    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }

    public function dateFormat(string $date_format)
    {
        $this->_date_format = $date_format;
        return $this;
    }
    public function hideLabel()
    {
        $this->_hide_label = true;
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

    public function loadDataFrom(string $field)
    {
        $this->_load_data_from = $field;
        return $this;
    }
    public function setDataSource(array $data_source)
    {
        $this->_data_source = $data_source;
        return $this;
    }


    private function loadData($item)
    {
        $dt = @$item[$this->_field];
        if (!empty($this->_load_data_from)) {
            $_col_sg = explode('.', $this->_load_data_from);
            if (count($_col_sg) > 1 && $_col_sg[1] == 'count') {
                $dt = count($item[$_col_sg[0]]);
            } else if (count($_col_sg) > 1) {
                $_fd = $item;
                foreach ($_col_sg as $key => $value) {

                    if (isset($_col_sg[$key - 1]) && $_col_sg[$key - 1] == '*') {
                        $_fd = array_map(function ($it) use ($value) {
                            if ($this->_date_format) {
                                return Carbon::parse($it[$value])->format($this->_date_format);
                            } else {
                                return $it[$value];
                            }
                        }, $_fd);
                    } else if ($value != '*' && $_fd) {
                        if ($this->_date_format) {
                            $_fd = Carbon::parse($_fd[$value])->format($this->_date_format);
                        } else {
                            $_fd = $_fd[$value];
                        }
                    }
                }
                $dt = $_fd;

            } else if (!empty($this->_date_format)) {
                $dt = Carbon::parse($item[$this->_load_data_from])->format($this->_date_format);
            } else {
                $dt = $item[$this->_load_data_from];
            }

        } else if (!empty($this->_date_format)) {
            $dt = Carbon::parse($item[$this->_field])->format($this->_date_format);
        }

        return $dt;

    }


    public function render(array $data_source = [])
    {
        $_data_source = empty($data_source) ? $this->_data_source : $data_source;
        $data = [
            'label' => empty($this->_label) ? str($this->_field)->replace('_', ' ')->replace('-', ' ')->ucfirst() : $this->_label,
            'field' => $this->_field,
            'hide_label' => $this->_hide_label,
            'value' => $this->loadData($_data_source),
        ];

        return [
            'props' => $this->onRender($data),
            'type' => $this->_type,
        ];
    }
}