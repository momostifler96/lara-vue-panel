<?php

namespace LVP\Widgets\DataWidgets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Enums\ActionMenuType;
use LVP\Enums\DataFilterType;
use LVP\Facades\TableColumn;
use LVP\Facades\TableFilters\TableFilter;
use LVP\Widgets\DataWidgets\Actions\DataActionMenu;
use LVP\Widgets\LVPWidget;

class DataGridWidget extends LVPWidget
{

    protected bool $_has_filter = true;
    protected DataFilter $_filter;
    protected int $_col_span = 3;
    protected int $_gap = 3;
    protected int $_cols = 6;

    protected array $_columns;
    protected array $_actions;
    protected array $_filters;
    protected DataFilterType $_filter_type = DataFilterType::POPOVER;
    protected string $_widget_type = 'data-grid';
    protected bool $_auto_submit_filter = true;
    protected string $_action_type = 'dropdown';
    protected array $_group_actions;
    protected string $_group_action_type = 'dropdown';
    protected string $_api_url;
    protected string $_primary_key = 'id';
    protected string $_card_type = 'place_holder';
    protected array $_data;

    protected array $_props_fields = [];
    protected array $_data_fields = [];
    protected bool $_paginated = false;
    protected bool $_has_action = false;
    /**
     * Summary of _column
     * @var TableColumn[] $_columns
     */


    public function __construct(array|Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $data, string $primary_key = 'id')
    {
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $this->_data['items'] = $data->items();
            $this->_data['pagination'] = [
                'total_items' => $data->total(),
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'path' => $data->path(),
                'per_page' => $data->perPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ];
            $this->_paginated = true;
        } else {
            $this->_data['item'] = $data;
        }
        $this->_primary_key = $primary_key;
    }
    public static function make(array|Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $data, string $primary_key = 'id')
    {
        return new static($data, $primary_key);
    }


    protected function fixeLastColumn()
    {
        $this->_fixe_last_column = true;
        return $this;
    }
    protected function fixeFirstColumn()
    {
        $this->_fixe_first_column = true;
        return $this;
    }
    public function cardType(string $card_type)
    {
        $this->_card_type = $card_type;
        return $this;
    }
    public function data(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    public function autoSubmitFilter(bool $auto_submit)
    {
        $this->_auto_submit_filter = $auto_submit;
        return $this;

    }
    public function filterType(DataFilterType $filter_type)
    {
        $this->_filter_type = $filter_type;
        return $this;

    }
    public function filters(array $filters)
    {
        $this->_filters = $filters;
        return $this;


    }
    public function actions(array $actions)
    {
        $this->_actions = $actions;
        return $this;
    }
    public function pagination(array $pagination)
    {
        $this->_data['pagination'] = $pagination;
        return $this;
    }
    public function actionMenuType(ActionMenuType $type)
    {
        $this->_action_type = $type->value;
        return $this;
    }
    public function groupActionMenuType(ActionMenuType $type)
    {
        $this->_group_action_type = $type->value;
        return $this;
    }
    public function actionsGroup(array $actions)
    {
        $this->_group_actions = $actions;
        return $this;
    }
    public function apiUrl(string $url)
    {
        $this->_api_url = $url;
        return $this;
    }
    public function propsFields(array $propsFields)
    {
        $this->_props_fields = $propsFields;
        return $this;
    }
    public function dataField(array $data_fields)
    {
        $this->_data_fields = $data_fields;
        return $this;
    }
    public function beforeRender(array $data): array
    {
        $columns = array_map(fn($item) => $item->render(), $this->_data_fields);
        $this->_data['items'] = $this->getColumnsData($columns);

        if (!empty($this->_filters)) {
            $filter = (new DataFilter());
            $filter->filters($this->_filters)->autoSubmit($this->_auto_submit_filter);
            $data['filter'] = $filter->render();
            $data['filter_type'] = $this->_filter_type->value;
        }
        $data['card_type'] = $this->_card_type;
        $data['data'] = $this->_data;
        $data['gap'] = $this->_gap;
        $data['cols'] = $this->_cols;
        $data['paginated'] = $this->_paginated;
        $data['api_url'] = empty($this->_api_url) ? null : $this->_api_url;
        $data['group_action'] = empty($this->_group_action_type) ? null : (new DataActionMenu())->type($this->_group_action_type)->actions($this->_group_actions)->render();

        return $data;
    }

    public function getTableActionsColumn(): array
    {
        return [
            'type' => 'actions',
            'label' => 'Actions',
            'field' => 'actions',
            'align' => 'right',
            'data' => $this->getTableActions(),
        ];
    }
    public function getTableActions(): array
    {

        return (new DataActionMenu())->type($this->_action_type)->actions($this->_actions)->render();
    }

    protected function getDataFromColumn(array $columns)
    {
        return array_map(function ($it) use ($columns) {
            $col = [];
            for ($i = 0; $i < count($columns); $i++) {
                $col_seg = explode('.', $columns[$i]['field']);
                if (count($col_seg) > 1) {
                } else {
                    $col[$columns[$i]['field']] = $it[$columns[$i]['field']];
                }
            }

            return $col;
        }, $this->_data);
    }

    private function getColumnsData(array $columns)
    {
        return array_map(function ($item) use ($columns) {
            $_cols = [
                'id' => $item[$this->_primary_key],
            ];
            $this->getTableColdata($item, $columns, $_cols);
            return $_cols;
        }, $this->_data['items']);
    }

    private function getTableColdata(mixed $item, array $columns, &$_cols)
    {
        foreach ($columns as $col) {
            if (isset($_cols[$col['field']])) {
                continue;
            }
            $_col_sg = explode('.', $col['load_data_from']);
            if (count($_col_sg) > 1) {
                $_fd = $item;
                if ($_col_sg[1] == 'count') {
                    $_cols[$col['field']] = $item[$_col_sg[0]]->count();
                } else if ($_col_sg[1] == 'sum') {
                    $_cols[$col['field']] = $item[$_col_sg[0]]->pluck($_col_sg[2])->sum();
                } else {
                    foreach ($_col_sg as $key => $value) {
                        if (isset($_col_sg[$key - 1]) && $_col_sg[$key - 1] == '*') {
                            $_fd = $_fd->map(function ($it) use ($value, $col) {
                                return $col['date_format'] ? Carbon::parse($it[$value])->format($col['date_format']) : $it[$value];
                            });
                        } else if ($value != '*' && $_fd) {
                            $_fd = isset($col['date_format']) ? Carbon::parse($_fd[$value])->format($col['date_format']) : $_fd[$value];
                        }
                    }
                    $_cols[$col['field']] = $_fd;
                }

            } else {
                if (!empty($col['date_format'])) {
                    $_cols[$col['field']] = Carbon::parse($item[$col['field']])->format($col['date_format']);
                } else {
                    $_cols[$col['field']] = $item[$col['field']];
                }
            }
        }
    }
    private function getPropsData(mixed $item, &$_cols)
    {
        $_props = [];
        foreach ($this->_props_fields as $col) {
            if (isset($_props[$col['field']])) {
                continue;
            }
            $_col_sg = explode('.', $col['load_data_from']);
            if (count($_col_sg) > 1) {
                $_fd = $item;
                if ($_col_sg[1] == 'count') {
                    $_props[$col['field']] = $item[$_col_sg[0]]->count();
                } else {
                    foreach ($_col_sg as $key => $value) {
                        if (isset($_col_sg[$key - 1]) && $_col_sg[$key - 1] == '*') {
                            $_fd = $_fd->map(function ($it) use ($value, $col) {
                                return $it[$value];
                            });
                        } else if ($value != '*' && $_fd) {
                            $_fd = $_fd[$value];
                        }
                    }
                    $_props[$col['field']] = $_fd;

                }

            } else {
                $_props[$col['field']] = $item[$col['field']];
            }

        }
        $_cols['props'] = $_props;
    }
}
