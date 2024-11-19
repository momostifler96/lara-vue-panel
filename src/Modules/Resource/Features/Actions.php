<?php

namespace LVP\Modules\Resource\Features;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LVP\Enums\LVPAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LVP\Form\FileUploadField;
use LVP\Form\ImageUploadField;
use LVP\Modules\Panel\Panel;
use LVP\Utils\CreateLVPAction;
use LVP\Widgets\FormWidget\Fields\FileUploadFieldWidget;
use LVP\Widgets\FormWidget\Fields\SwithToggleFieldWidget;

trait Actions
{
    private array $_request_files = [];
    private function buildModelData(Request $request, LVPAction $action = LVPAction::CREATE, array $old_data = []): array
    {
        $model_data = [];

        /**
         * @var \LVP\Widgets\FormWidget\Fields\FormFieldWidget[] $fields
         */

        $fields = $this->formFields();
        foreach ($fields as $key => $field) {
            if ($action->value == 'create' && $field->canfillOnCreate()) {

                if ($field instanceof SwithToggleFieldWidget && empty($request[$field->field()])) {
                    $model_data[$field->field()] = $field->onStore(0);
                } else if ($field instanceof FileUploadFieldWidget && !empty($request[$field->field()])) {
                    $this->saveFiles($model_data, $field->field(), $request);
                } else {
                    $field->onStoreData($model_data, $request);
                }
            } else if ($action->value == 'edit' && $field->canfillOnEdit()) {
                $field->onUpdateData($model_data, $request, $old_data);
            }
        }
        return $model_data;
    }

    public function buildSectionFields(&$formData, $section, Request $request)
    {
        $section->buildFieldsData($formData, $request);
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
                if ($this->form_type == 'page') {
                    Route::get($this->slug . '/create', fn(Request $request) => $this->create($request))->middleware($this->create_middlewares)->name('create');
                    Route::get($this->slug . '/edit/{id}', fn(Request $request, $id) => $this->edit($request, $id))->middleware($this->edit_middlewares)->name('edit');
                }
                Route::post($this->slug . '/store', fn(Request $request) => $this->store($request))->middleware($this->store_middlewares)->name('store');
                Route::post($this->slug . '/update', fn(Request $request) => $this->update($request))->middleware($this->update_middlewares)->name('update');
                Route::post($this->slug . '/exec-actions', fn(Request $request) => $this->execActions($request))->middleware($this->update_middlewares)->name('exec-actions');
                Route::get($this->slug . '/{id}', fn(Request $request, $id) => $this->show($request, $id))->middleware($this->show_middlewares)->name('show');
                Route::delete($this->slug . '/', fn(Request $request) => $this->delete($request))->middleware($this->delete_middlewares)->name('delete');
            });
    }

    function saveFiles(array &$formData, string $field, Request $request)
    {

        /**
         * @var \Illuminate\Http\UploadedFile $file
         */
        if (is_array($request[$field])) {
            foreach ($request[$field] as $file) {
                $formData[$field][] = Storage::disk(config('laravue-panel.uploaded-file-disk', 'public'))->url($file->store(null, ['disk' => config('laravue-panel.uploaded-file-disk', 'public')]));
            }
        } else {
            $file = $request[$field];
            $formData[$field] = Storage::disk(config('laravue-panel.uploaded-file-disk', 'public'))->url($file->store(null, ['disk' => config('laravue-panel.uploaded-file-disk', 'public')]));
        }
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
        $query = $this->buildQuery($request);
        $data = $this->dataWidget()->setQuery($query)->paginated()->render();
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
    private function buildQuery(Request $request): Builder
    {
        /**
         * @var Builder $query
         */
        $query = $this->model::query();
        $this->beforeBuildQuery($query, $request);

        $this->afterBuildQuery($query, $request);
        return $query;
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

    protected function buildActions(): array
    {
        return [
            'enable' => CreateLVPAction::make('enable', [$this, 'enableItem']),
        ];
    }

}