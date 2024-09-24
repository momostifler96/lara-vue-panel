<?php

namespace LVP\Widgets\DataWidgets\DataTable;

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
use LVP\Widgets\DataWidgets\DataFilter;
use LVP\Widgets\DataWidgets\DataWidget;
use LVP\Widgets\LVPWidget;
use Request;

class DataTableWidget extends DataWidget
{

    protected bool $_has_filter = true;
    protected DataFilter $_filter;
    protected int $_col_span = 3;
    protected array $_columns;
    protected array $_searchable_fields;
    protected DataFilterType $_filter_type = DataFilterType::POPOVER;
    protected string $_widget_type = 'data-table';

    protected bool $_fixe_first_column = false;
    protected bool $_fixe_last_column = false;
    /**
     * Summary of _column
     * @var TableColumn[] $_columns
     */


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


    public function beforeRender(array $data): array
    {
        $this->loadSearchableFields();
        $this->runFilterQuery();
        $this->loadData();
        $columns = array_map(fn($item) => $item->render(), $this->_columns);
        $this->_data['items'] = $this->getInfos($columns);

        if (!empty($this->_filters)) {
            $filter = (new DataFilter());
            $filter->filters($this->_filters)->autoSubmit($this->_auto_submit_filter);
            $data['filter'] = $filter->render();
            $data['filter_type'] = $this->_filter_type->value;
        }
        $data['fixe_first_column'] = $this->_fixe_first_column;
        $data['fixe_last_column'] = $this->_fixe_last_column;
        $data['columns'] = [...$columns, $this->getTableActionsColumn()];
        $data['data'] = $this->_data;
        $data['paginated'] = $this->_paginated > 0;
        $data['searchables'] = $this->_searchable_fields;
        $data['api_url'] = empty($this->_api_url) ? null : $this->_api_url;
        $data['bulk_actions'] = empty($this->_bulk_actions_type) ? null : (new DataActionMenu())->label($this->_bulk_actions_label)->type($this->_bulk_actions_type)->actions($this->_bulk_actions)->render();

        return $data;
    }

    private function getTableActionsColumn(): array
    {

        return [
            'type' => 'actions',
            'label' => 'Actions',
            'field' => 'actions',
            'align' => 'right',
            'data' => $this->getTableActions(),
        ];
    }


    private function getTableActions(): array
    {

        return (new DataActionMenu())->type($this->_action_type)->actions($this->_actions)->render();
    }

    private function getDataFromColumn(array $columns)
    {
        return array_map(function ($it) use ($columns) {
            $col = [];
            for ($i = 0; $i < count($columns); $i++) {
                $col_seg = explode('.', $columns[$i]['field']);
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

    protected function runFilterQuery()
    {

        $request = request();
        if ($request->has('search')) {
            $searchable_columns = $this->_searchable_fields;
            if (!empty($searchable_columns)) {
                $this->_query->whereAny($searchable_columns, 'LIKE', $request->get('search') . '%');
            }
        }
        $data_filters = $this->_filters;

        $request_array_data = $request->toArray();
        for ($i = 0; $i < count($data_filters); $i++) {
            $data_filters[$i]->apply($this->_query, $request_array_data);
        }


    }

    private function loadSearchableFields()
    {
        $fields = [];
        foreach ($this->_columns as $key => $field) {
            if ($field->isSearchable()) {
                $fields[] = $field->field();
            }
        }
        $this->_searchable_fields = $fields;

    }

    public function loadData()
    {
        if ($this->_paginated > 0) {
            $data = $this->_query->paginate($this->_paginated);
        } else {
            $data = $this->_query->get();
        }
        $this->setData($data);
    }


}
