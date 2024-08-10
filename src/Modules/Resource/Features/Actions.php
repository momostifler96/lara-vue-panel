<?php

namespace LVP\Modules\Resource\Features;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use LVP\Enums\LVPAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LVP\Form\FileUploadField;
use LVP\Form\ImageUploadField;
use LVP\Modules\Panel\Panel;
use LVP\Support\Info;
use LVP\Widgets\DataWidgets\DataTableWidget;

trait Actions
{
    private function buildModelData(Request $request, LVPAction $action = LVPAction::CREATE, array $old_data = []): array
    {
        $model_data = [];

        /**
         * @var \LVP\Widgets\FormWidget\Fields\FormFieldWidget[] $fields
         */

        $fields = $this->formFields();

        foreach ($fields as $key => $field) {
            if ($action->value == 'create' && $field->canfillOnCreate()) {
                $model_data[$field->field()] = $field->onStore($request[$field->field()]);
            } else if ($action->value == 'edit' && $field->canfillOnEdit()) {
                if (!empty($field->onEditData())) {
                    $_fields = explode('.', $field->onEditData());
                    $_fd = $old_data;
                    foreach ($_fields as $key => $value) {
                        if (isset($_fields[$key - 1]) && $_fields[$key - 1] == '*') {
                            $_fd = array_map(function ($it) use ($value) {
                                return $it[$value];
                            }, $_fd);
                        } else if ($value != '*') {
                            $_fd = $_fd[$value];
                        }
                    }
                    $model_data[$field->field()] = $_fd;
                    $model_data[$field->field()] = $field->onUpdate($request[$field->field()], $_fd);
                } else {
                    $model_data[$field->field()] = $field->onUpdate($request[$field->field()], @$old_data[$field->field()] ?? '');
                }
            }
        }

        return $model_data;
    }
    private function buildData($pagination, $cols): array
    {
        return [
            'items' => array_map(function ($item) use ($cols) {
                $_cols = [
                    'id' => $item->id,
                ];
                foreach ($cols as $key => $col) {
                    $_col_sg = explode('.', $col['load_data_from']);
                    if (count($_col_sg) > 1 && $_col_sg[1] == 'count') {
                        $_cols[$col['field']] = $item[$_col_sg[0]]->count();
                    } else if (count($_col_sg) > 1) {
                        $_fd = $item;
                        foreach ($_col_sg as $key => $value) {

                            if (isset($_col_sg[$key - 1]) && $_col_sg[$key - 1] == '*') {
                                $_fd = $_fd->map(function ($it) use ($value, $col) {
                                    if ($col['date_format']) {
                                        return Carbon::parse($it[$value])->format($col['date_format']);
                                    } else {
                                        return $it[$value];
                                    }
                                });
                            } else if ($value != '*' && $_fd) {
                                if ($col['date_format']) {
                                    $_fd = Carbon::parse($_fd[$value])->format($col['date_format']);
                                } else {
                                    $_fd = $_fd[$value];
                                }
                            }

                        }
                        $_cols[$col['field']] = $_fd;

                    } else {
                        if (!empty($col['date_format'])) {
                            $_cols[$col['field']] = $item[$col['field']]->format($col['date_format']);
                        } else if ($item[$col['type']] != 'group') {
                            $_cols[$col['field']] = $item[$col['field']];
                        }
                    }
                }
                return $_cols;
            }, $pagination->items()),
            'pagination' => [
                'total_items' => $pagination->total(),
                'total' => $pagination->total(),
                'current_page' => $pagination->currentPage(),
                'path' => $pagination->path(),
                'per_page' => $pagination->perPage(),
                'from' => $pagination->firstItem(),
                'to' => $pagination->lastItem(),
            ],
        ];
    }
    private function withTransaction(callable $callback, callable $onCommit = null, callable $onError = null)
    {

        if ($this->enable_transaction) {
            try {
                DB::beginTransaction();
                $callback();
                DB::commit();
                return $onCommit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error("Something went wrong on :" . static::class . '@' . __METHOD__ . ':' . $th->getMessage());
                return $onError($th);
            }
        } else {
            try {
                $callback();
                return $onCommit();
            } catch (\Throwable $th) {
                return $onError($th);
            }

        }
    }

    public function registerRoutes()
    {
        Route::middleware($this->middlewares)
            ->as($this->route_name . '.')
            ->group(function () {
                Route::get($this->slug . '/', fn(Request $request) => $this->index($request))->name('index');
                Route::get($this->slug . '/create', fn(Request $request) => $this->create($request))->middleware($this->create_middlewares)->name('create');
                Route::post($this->slug . '/store', fn(Request $request) => $this->store($request))->middleware($this->store_middlewares)->name('store');
                Route::get($this->slug . '/edit/{id}', fn(Request $request, $id) => $this->edit($request, $id))->middleware($this->edit_middlewares)->name('edit');
                Route::post($this->slug . '/update', fn(Request $request) => $this->update($request))->middleware($this->update_middlewares)->name('update');
                Route::get($this->slug . '/{id}', fn(Request $request, $id) => $this->show($request, $id))->middleware($this->show_middlewares)->name('show');
                Route::delete($this->slug . '/', fn(Request $request) => $this->delete($request))->middleware($this->delete_middlewares)->name('delete');
            });
    }

    /**
     * @return void
     */
    protected function buildLabelsAndTitles()
    {
        $base_name = class_basename(get_called_class());
        $class_base_name = extractWordBefore($base_name, 'Resource');
        $class_base_name_plural = str($class_base_name)->plural();

        if (empty($this->label)) {
            $this->label = $class_base_name_plural->kebab()->replace('-', ' ')->lower()->ucfirst();
        }
        if (empty($this->short_label)) {
            $this->short_label = $this->label;
        }
        if (empty($this->plural_label)) {
            $this->plural_label = $class_base_name_plural;
        }

        if (empty($this->menu_label)) {
            $this->menu_label = $this->plural_label;
        }

        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->plural_label);
        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->plural_label);

        $this->slug = $this->_panel_route_path . '/' . str(!empty($this->slug) ? $this->slug : $class_base_name_plural)->kebab()->lower()->toString();
        $this->route_name = $this->_panel_route_name . '.' . str(!empty($this->route_name) ? $this->route_name : $class_base_name_plural)->kebab()->lower()->toString();
    }


    private function trGender(string $type)
    {
        if ($this->gender == 'male') {
            switch ($type) {
                case 'this':
                    return 'ce';
                case 'a':
                    return 'un';
                default:
                    return $type;
            }
        } else {
            switch ($type) {
                case 'this':
                    return 'cette';
                case 'a':
                    return 'une';
                default:
                    return $type;
            }
        }
    }

    protected function setupPanel(Panel $panel_provider)
    {
        $this->_panel_route_name = $panel_provider->getPanelRouteName();
        $this->_panel_route_path = $panel_provider->getPanelRoutePath();
        $this->local = $panel_provider->getPanelLocal();
    }

    public function getPageTitles()
    {
        return [
            'title' => $this->page_title,
            'label' => $this->label,
            'info' => 'Info ' . $this->label,
            'plural_label' => $this->plural_label,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->plural_label,
            'menu_label' => $this->menu_label
        ];
    }

    public function buildColumnsData(Builder $query, $columns)
    {
        foreach ($columns as $key => $column) {
            if (str_starts_with($column['field'], 'related.')) {
                $related = explode('.', substr($column['field'], strlen('related.')));
                $query->with($related[0]);
            } else if (str_starts_with($column['field'], 'count.')) {
                $related = substr($column['field'], strlen('count.'));
                $query->withCount($related);
            }
        }
    }
    private function tr($word, $tr, $prefix = null)
    {
        return str_replace(['{gender}', '{label}'], [$this->trGender('a'), $word], lvp_translation($tr, $this->_translations));
    }
    private function buildDataWidget(Request $request)
    {
        $columns = $this->buildDataColumns();
        $query = $this->buildQuery($request, $columns);
        $data = DataTableWidget::make($query->paginate(), $this->model_primary_key)->propsFields($this->buildItemFormFields())->columns($this->dataColumns())->filters($this->dataFilters())->actions($this->dataActions())->actionsGroup($this->dataActionsGroup())->render();

        // $data->withQuery($this->query);
        // $data->withColumns($this->columns);
        // $data->withActions($this->actions);

        return $data;
    }
    protected function buildDataColumns(): array
    {
        return array_map(fn($_table_column) => $_table_column->render(), $this->dataColumns());
    }

    private function buildBeforeDataWidget(Request $request)
    {
        return array_map(fn($_table_column) => $_table_column->render(), $this->beforeDataWidgets());
    }
    private function buildQuery(Request $request, $columns): Builder
    {
        /**
         * @var Builder
         */
        $query = $this->model::query();


        if ($request->has('search')) {
            $searchable_columns = $this->getSearchableFields();
            if (!empty($searchable_columns)) {
                $query->whereAny($searchable_columns, 'LIKE', $request->get('search') . '%');
            }
        }
        $data_filters = $this->dataFilters();

        $request_array_data = $request->toArray();
        for ($i = 0; $i < count($data_filters); $i++) {
            $data_filters[$i]->apply($query, $request_array_data);
        }

        foreach ($columns as $key => $column) {
            if (str_starts_with($column['field'], 'related.')) {
                $related = explode('.', substr($column['field'], strlen('related.')));
                $query->with($related[0]);
            } else if (str_starts_with($column['field'], 'count.')) {
                $related = substr($column['field'], strlen('count.'));
                $query->withCount($related);
            }
        }

        return $query->with($this->model_with);
    }
    private function buildAfterDataWidget(Request $request)
    {
        return array_map(fn($_table_column) => $_table_column->render(), $this->afterDataWidgets());
    }
    private function buildInfoWidgets(array $data)
    {
        return array_map(function ($info) use ($data) {
            return $info->render($data);
        }, $this->infoList());
    }
    private function buildItemFormFields()
    {
        return array_map(fn($it) => $it->load(), $this->formFields());
    }

    public function getFieldRuls($action = 'create')
    {
        $rules = [];

        foreach ($this->formFields() as $key => $field) {
            if ($field instanceof ImageUploadField || $field instanceof FileUploadField) {
                $rules[$field->field() . '.*'] = $field->getRules($action);
            } else {
                $rules[$field->field()] = $field->getRules($action);
            }
        }
        return $rules;
    }

}