<?php

namespace LVP\Widgets\DataWidgets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Enums\ActionMenuType;
use LVP\Enums\DataFilterType;
use LVP\Facades\TableColumn;
use LVP\Facades\TableFilters\TableFilter;
use LVP\Widgets\DataWidgets\Actions\DataActionMenu;
use LVP\Widgets\LVPWidget;
use Request;

class DataWidget extends LVPWidget
{

    protected bool $_has_filter = true;
    protected DataFilter $_filter;
    protected int $_col_span = 3;

    protected array $_columns;
    protected array $_actions;
    protected array $_filters;
    protected DataFilterType $_filter_type = DataFilterType::POPOVER;
    protected string $_widget_type = 'data-table';
    protected bool $_auto_submit_filter = true;
    protected string $_action_type = 'dropdown';
    protected array $_bulk_actions;
    protected string $_bulk_actions_label = 'Bulk Actions';
    protected string $_bulk_actions_type = 'dropdown';
    protected string $_api_url;
    protected string $_primary_key = 'id';
    protected array $_data;

    protected Builder $_query;
    protected array $_props_fields = [];
    /**
     * Summary of _hidden_fields
     * @var DataHiddenField[]
     */
    protected array $_hidden_fields = [];
    protected int $_paginated = 0;
    protected bool $_has_action = false;

    /**
     * Summary of _column
     * @var TableColumn[] $_columns
     */


    public function __construct(string $primary_key = 'id')
    {
        $this->_primary_key = $primary_key;
    }
    public static function make(string $primary_key = 'id')
    {
        return new static($primary_key);
    }
    public function data(array|Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $data)
    {
        $this->setData($data);
        return $this;
    }
    public function setData(array|Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator $data)
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
        } else {
            $this->_data['items'] = $data;
        }
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
    public function columns(array $columns)
    {
        $this->_columns = $columns;
        return $this;
    }
    /**
     * Summary of hiddenFields
     * @param DataHiddenField[] $fields
     * @return static
     */
    public function hiddenFields(array $fields)
    {
        $this->_hidden_fields = $fields;
        return $this;
    }
    public function bulkActionLabel(string $label)
    {
        $this->_bulk_actions_label = $label;
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
    public function bulkActionsType(ActionMenuType $type)
    {
        $this->_bulk_actions_type = $type->value;
        return $this;
    }
    public function bulkActions(array $actions)
    {
        $this->_bulk_actions = $actions;
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
    public function beforeRender(array $data): array
    {
        return $data;
    }

    protected function getInfos(array $columns)
    {
        return array_map(function ($item) use ($columns) {
            $_cols = [
                'id' => $item[$this->_primary_key],
            ];
            $this->getInfoLineData($item, $columns, $_cols);
            $this->getPropsData($item, $_cols);
            return $_cols;
        }, $this->_data['items']);
    }

    private function getInfoLineData(mixed $item, array $columns, &$_cols)
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
                            $_fd = $col['date_format'] ? Carbon::parse($_fd[$value])->format($col['date_format']) : $_fd[$value];
                        }
                    }
                    $_cols[$col['field']] = $_fd;
                }

            } else {
                if (!empty($col['date_format'])) {
                    $_cols[$col['field']] = Carbon::parse($item[$col['field']])->format($col['date_format']);
                } else if ($col['type'] != 'group') {
                    $_cols[$col['field']] = $item[$col['field']];
                } else if ($col['type'] == 'group' && isset($col['groups'])) {
                    foreach ($col['groups'] as $key => $group) {
                        $this->getTableColdata($item, $group, $_cols);
                    }
                }
            }
        }
    }

    public function getTableColdata($item, $group, &$_cols)
    {
        foreach ($group as $col) {
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
                            $_fd = $col['date_format'] ? Carbon::parse($_fd[$value])->format($col['date_format']) : $_fd[$value];
                        }
                    }
                    $_cols[$col['field']] = $_fd;
                }

            } else {
                if (!empty($col['date_format'])) {
                    $_cols[$col['field']] = Carbon::parse($item[$col['field']])->format($col['date_format']);
                } else if ($col['type'] != 'group') {
                    $_cols[$col['field']] = $item[$col['field']];
                } else if ($col['type'] == 'group' && isset($col['groups'])) {
                    foreach ($col['groups'] as $key => $group) {
                        $this->getTableColdata($item, $group, $_cols);
                    }
                }
            }
        }

    }

    private function getPropsData(mixed $item, &$_cols)
    {
        $_props = [];
        // dd($this->_props_fields);
        foreach ($this->_hidden_fields as $col) {
            if (isset($_cols[$col->field()])) {
                continue;
            }
            $_col_sg = explode('.', $col->loadDataFrom());
            if (count($_col_sg) > 1) {
                $_fd = $item;
                if ($_col_sg[1] == 'count') {
                    $_cols[$col->field()] = $item[$_col_sg[0]]->count();
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
                    $_cols[$col->field()] = $_fd;

                }

            } else {
                $_cols[$col->field()] = $item[$col->field()];
            }
        }
    }

    public function paginated($paginate = 15)
    {
        $this->_paginated = $paginate;
        return $this;
    }

    public function setQuery(Builder $query, $primary_key = 'id')
    {
        $this->_query = $query;
        $this->_primary_key = $primary_key;
        return $this;
    }



}
