<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Enums\ActionMenuType;
use LVP\Facades\TableColumn;
use LVP\Facades\TableFilters\TableFilter;
use LVP\Widgets\DataWidgets\Actions\DataActionMenu;
use LVP\Widgets\LVPWidget;

class DataTableWidget extends LVPWidget
{

    protected bool $_has_filter = true;
    protected DataFilter $_filter;

    protected array $_columns;
    protected array $_actions;
    protected array $_filters;
    protected string $_widget_type = 'dataTable';
    protected string $_action_type;
    protected array $_group_actions;
    protected string $_group_action_type;
    protected string $_api_url;
    protected array $_data;

    protected bool $_paginated = false;
    protected bool $_has_action = false;
    protected bool $_fixe_first_column = false;
    protected bool $_fixe_last_column = false;
    /**
     * Summary of _column
     * @var TableColumn[] $_columns
     */


    public function __construct()
    {
    }
    public static function make()
    {
        return new static();
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
    public function data(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    public function filter(array $filters)
    {
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
    public function beforeRender(array $data): array
    {
        $columns =  array_map(fn ($item) => $item->render(), $this->_columns);
        if ($this->_has_action) {
            $columns = [...$columns, $this->getTableActionsColumn()];
        }
        // dd($this->getDataFromColumn($columns));

        $data['fixe_first_column'] =   $this->_fixe_first_column;
        $data['fixe_last_column'] =   $this->_fixe_last_column;
        $data['filter'] = empty($this->_filter) ? null : $this->_filter;
        $data['columns'] = $columns;
        $data['data'] = [
            'items' => $this->_data,
            'pagination' => null,
        ];
        $data['paginated'] = $this->_paginated;
        $data['api_url'] = empty($this->_api_url) ? null : $this->_api_url;
        $data['group_action'] = empty($this->_group_action_type) ? null : (new DataActionMenu())->type($this->_group_action_type)->actions($this->_group_actions)->render();

        return  $data;
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



    protected function getDataFromColumn(array $columns)
    {
        return array_map(function ($it) use ($columns) {
            $col = [];
            for ($i = 0; $i < count($columns); $i++) {
                // dd();
                $col_seg = explode('.', $columns[$i]['field']);
                // dd($col_seg);

                if (count($col_seg) > 1) {
                    dd($col_seg);

                    // $col[$columns[$i]['field']] = $it[$columns];
                } else {
                    $col[$columns[$i]['field']] = $it[$columns[$i]['field']];
                }
            }

            return $col;
        }, $this->_data);
    }
}
