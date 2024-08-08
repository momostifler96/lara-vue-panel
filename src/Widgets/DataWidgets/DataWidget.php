<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Enums\ActionMenuType;
use LVP\Facades\TableFilters\TableFilter;
use LVP\Widgets\DataWidgets\Actions\DataActionMenu;

class DataWidget
{

    protected bool $_has_filter = true;
    protected DataFilter $_filter;

    protected array $_columns;
    protected array $_actions;
    protected array $_filters;
    protected array $_group_actions;
    protected string $_label;
    protected string $_type;
    protected string $_name;
    protected string $_action_type;
    protected string $_group_action_type;
    protected string $_api_url;
    protected array $_data;
    protected bool $_fixe_last_column = false;
    protected bool $_fixe_first_column = false;
    protected bool $_paginated = false;
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    public function dataColumns(array $columns)
    {
        $this->_columns = $columns;
        return $this;
    }

    public function filter(array $filters)
    {
        $this->_filter = (new DataFilter());
        return $this->_filter->filters($filters);
    }
    public function actions(array $actions)
    {
        $this->_actions = $actions;
        return $this;
    }
    public function paginated(bool $paginated = true)
    {
        $this->_paginated = $paginated;
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


    public function _backRender()
    {
        return [
            'columns' => $this->_columns,
            'filter' => $this->_filter,
            'type' => $this->_type,
        ];
    }
    public function getTableActionsColumn(): array
    {

        return [
            'type' => 'actions',
            'label' => '',
            'field' => 'actions',
            'align' => 'right',
            'data' => $this->getTableActions(),
        ];
    }
    public function getTableActions(): array
    {

        return (new DataActionMenu())->type($this->_action_type)->actions($this->_actions)->render();
    }
    protected function _render()
    {
        // dd($this);
        $columns = array_map(fn($item) => $item->render(), $this->_columns);

        $columns = [...$columns, $this->getTableActionsColumn()];
        // dd($columns);
        return [
            'columns' => $columns,
            'filter' => isset($this->_filter) ? $this->_filter->render() : null,
            'group_action' => (new DataActionMenu())->type($this->_group_action_type)->actions($this->_group_actions)->render(),
            'label' => $this->_label ?? '',
            'fixe_last_column' => $this->_fixe_last_column,
            'fixe_first_column' => $this->_fixe_first_column,
            'type' => $this->_type,
            'api_url' => $this->_api_url ?? null,
            'paginated' => $this->_paginated,
            'data' => $this->_data,
        ];
    }
    protected function backRenderPipe(array $data)
    {
        return $data;
    }
    protected function renderPipe(array $data)
    {
        return $data;
    }

    public function backRender()
    {
        return $this->backRenderPipe($this->_backRender());
    }
    public function render()
    {
        return $this->renderPipe($this->_render());
    }

    private function getColumnsData(array $columns)
    {
        return array_map(function ($item) use ($columns) {
            $_cols = [
                'id' => $item[$this->_primary_key],
            ];
            $this->getTableColdata($item, $columns, $_cols);
            $this->getPropsData($item, $_cols);
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
        foreach ($this->_props_fields as $col) {
            if (isset($_cols[$col['field']])) {
                continue;
            }
            $_col_sg = explode('.', $col['load_data_from']);
            if (count($_col_sg) > 1) {
                $_fd = $item;
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
            } else {
                $_props[$col['field']] = $item[$col['field']];
            }

        }
        $_cols['props'] = $_props;
    }
}
