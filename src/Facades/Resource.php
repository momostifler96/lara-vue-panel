<?php

namespace LVP\Facades;

use LVP\Form\FileUploadField;
use LVP\Form\ImageUploadField;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use LVP\Enums\ActionMenuType;
use LVP\Enums\LVPAction;
use LVP\Widgets\DataWidgets\DataGrid;
use LVP\Widgets\DataWidgets\DataTable;
use LVP\Widgets\DataWidgets\DataWidget;
use LVP\Widgets\DataWidgets\Filters\DataFilterField;
use LVP\Widgets\LVPWidget;

class Resource
{
    protected static Resource $_instance;
    protected string $_model = Model::class;
    protected array $_model_appends = [];
    protected array $_model_with = [];
    protected string $_model_primary_key = 'id';
    protected DataWidget $_data_widget;
    protected Panel $_panel;
    protected int $menu_position = 0;
    protected string $menu_icon = 'stack';
    protected string $gender = 'male';
    protected string $menu_group;
    protected ActionMenuType $_action_type = ActionMenuType::DROPDOWN;
    protected ActionMenuType $_group_action_type = ActionMenuType::DROPDOWN;

    protected string $slug = '';
    protected string $route_name = '';
    protected string $route_path = '';
    protected string $_panel_route = 'admin';
    protected string $_data_widget_type = 'table';
    protected string $_locale = 'en';
    /**
     * Summary of _widgets
     * @var LVPWidget[]
     */
    protected array $_widgets = [];
    public bool $disabled = false;
    protected bool $modal_form = false;
    protected string $index_page_title;
    protected string $create_page_title;
    protected string $edit_page_title;
    protected string $page_title;
    protected string $meta_title;
    protected string $label;
    protected string $short_label;
    protected string $submit_button_label;
    protected string $navigation_label;
    protected string $plural_label;
    #Middleware
    protected array $_middlewares;
    protected array $index_middlewares;
    protected array $create_middlewares;
    protected array $edit_middlewares;
    protected array $_fields_rules;
    protected $_translations = [];
    protected $enable_transaction = true;
    protected bool $show_on_navigation = true;

    public function __construct(Panel $panel)
    {
        $this->_panel = $panel;
        $locale = config('app.locale');
        $this->_locale = $locale;
        $tr = require __DIR__ . './../Translations/' . $locale . '.php';
        // dd($tr);
        $this->_translations = $tr;
    }
    protected function settings(Resource $resource)
    {
    }
    public function setup()
    {
        $this->settings($this);
        $this->makeNames();
    }
    protected function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }
    protected function setPluralLabel(string $label)
    {
        $this->plural_label = $label;
        return $this;
    }
    protected function setShortLabel(string $label)
    {
        $this->short_label = $label;
        return $this;
    }
    protected function disable(bool $disable = true)
    {
        $this->disabled = $disable;
        return $this;
    }
    protected function isModalForm()
    {
        $this->modal_form = true;
        return $this;
    }
    protected function setMenuLabel(string $label)
    {
        $this->navigation_label = $label;
        return $this;
    }
    protected function setMenuPosition(int $position)
    {
        $this->menu_position = $position;
        return $this;
    }
    protected function menuGroup(string $group)
    {

        $this->menu_group = $group;
        return $this;
    }
    protected function menuIcon(string $icon)
    {

        $this->menu_icon = $icon;
        return $this;
    }
    protected function setPageTitle(string $title)
    {

        $this->page_title = $title;
        return $this;
    }
    protected function setPageSlug(string $slug)
    {

        $this->slug = $slug;
        return $this;
    }
    protected function setMetaTitle(string $title)
    {

        $this->meta_title = $title;
        return $this;
    }
    protected function notShowInMenu()
    {
        $this->show_on_navigation = false;
        return $this;
    }
    protected function setRouteName(string $name)
    {
        $this->route_name = $name;
        return $this;
    }
    protected function middlewares(array $middlewares)
    {

        $this->_middlewares[] = $middlewares;
        return $this;
    }
    protected function indexMiddlewares(array $middlewares)
    {

        $this->index_middlewares[] = $middlewares;
        return $this;
    }
    protected function createMiddlewares(array $middlewares)
    {
        $this->create_middlewares[] = $middlewares;
        return $this;
    }
    protected function dataWidgetType(string $type = 'table')
    {
        $this->_data_widget_type = $type;
        return $this;
    }
    protected function editMiddlewares(array $middlewares)
    {

        $this->edit_middlewares[] = $middlewares;
        return $this;
    }
    public function getIndexPageTitle()
    {
        return str(empty($this->index_page_title) ? $this->page_title : $this->index_page_title)->ucfirst();
    }
    private function tr($word, $tr, $prefix = null)
    {
        return str_replace(['{gender}', '{label}'], [$this->trGender('a'), $word], lvp_translation($tr, $this->_translations));
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
    public function getCreatePageTitle()
    {
        return $this->create_page_title ?? str_replace(['{gender}', '{label}'], [$this->trGender('a'), $this->getShortLabel()->lower()], $this->_translations['resource']['add']);
    }

    public function getEditPageTitle()
    {
        return $this->edit_page_title ?? str_replace(['{gender}', '{label}'], [$this->trGender('a'), $this->getShortLabel()->lower()], $this->_translations['resource']['edit']);
    }
    public function getLabel()
    {
        return str(empty($this->label) ? $this->page_title : $this->label)->ucfirst();
    }
    public function getShortLabel()
    {
        return str(empty($this->short_label) ? $this->page_title : $this->short_label)->ucfirst();
    }


    public function getPluralLabel()
    {
        return str(empty($this->plural_label) ? $this->page_title : $this->plural_label)->ucfirst();
    }


    public function getModel()
    {
        return $this->_model;
    }
    public function getModelBaseName()
    {
        return str(class_basename($this->_model))->kebab()->replace('-', ' ')->singular()->lower();
    }


    public function getEnableTranction()
    {
        return $this->enable_transaction;
    }

    protected function relations(): array
    {
        return [];
    }

    /**
     * Summary of widgets
     * @return LVPWidget[]
     */
    protected function beforeDataWidgets(): array
    {
        return [];
    }
    /**
     * Summary of widgets
     * @return LVPWidget[]
     */
    protected function afterDataWidgets(): array
    {
        return [];
    }

    private $_fields = [];


    protected function backendFields(): array
    {
        return [];
    }


    protected function fieldsRules()
    {
        return $this->_fields_rules = array_map(fn($field) => $field->getRules(), $this->_fields);
    }


    protected function formFields(): array
    {
        return [];
    }
    public function loadFormFields($action)
    {
        $fields = [
            'form_render' => [],
            'form_data' => [],
        ];

        foreach ($this->formFields() as $key => $field) {
            if (!$field->isHiddenOnCreate()) {
                $fields['form_render'][] = $field->render($action);
                $fields['form_data'][$field->field()] = $field->defaultValue();
            }
        }
        return $fields;
    }
    function lvp_translation($word, $translations = null)
    {
        $_tt = explode('.', $word, );
        $_translations = $translations;
        $_tr = $translations;
        $locale = config('app.locale');
        if (!$translations) {
            $_tr = require __DIR__ . '/Translations/' . $locale . '.php';
        }

        foreach ($_tt as $key => $value) {
            $_tr = $_tr[$value];
        }

        return $_tr;
    }

    public function loadUpdateFormFields($data)
    {
        $fields = [
            'form_render' => [],
            'form_data' => [],
        ];

        foreach ($this->formFields() as $key => $field) {
            if (!$field->isHiddenOnEdit()) {
                $fields['form_render'][] = $field->render();
                if (!empty($field->onEditData())) {
                    $_fields = explode('.', $field->onEditData());
                    $_fd = $data;
                    foreach ($_fields as $key => $value) {
                        if (isset($_fields[$key - 1]) && $_fields[$key - 1] == '*') {
                            $_fd = array_map(function ($it) use ($value) {
                                return $it[$value];
                            }, $_fd);
                        } else if ($value != '*') {
                            $_fd = $_fd[$value];
                        }
                    }
                    $fields['form_data'][$field->field()] = $_fd;
                } else {
                    $fields['form_data'][$field->field()] = $data[$field->field()];
                }
            }
        }

        return $fields;
    }
    protected function loadBackFields($action)
    {
        return array_map(fn($field) => $field->backRender(), $this->formFields());
    }
    public function getFormData()
    {
        $fields = [];

        foreach ($this->formFields() as $key => $field) {
            $fields[$field->field()] = $field->defaultValue();
        }

        return $fields;
    }
    public function getFieldRuls($action = 'create')
    {
        $rules = [];
        /**
         * @var FormField $field
         */
        foreach ($this->formFields() as $key => $field) {
            if ($field instanceof ImageUploadField || $field instanceof FileUploadField) {
                $rules[$field->field() . '.*'] = $field->getRules($action);
            } else {
                $rules[$field->field()] = $field->getRules($action);
            }
        }

        // dd($rules);
        return $rules;
    }

    public function getFilledFormFields()
    {
        // dd($this->formFields()[0]->getFieldType());
        return array_map(fn($field) => ['field' => $field->field(), 'type' => $field->getFieldType()], $this->formFields());
    }
    protected function dataColumns(): array
    {
        return [];
    }
    protected function tableColumns(): array
    {
        return [];
    }
    protected function loadTableColumns(): array
    {
        return array_map(fn($_table_column) => $_table_column->render(), $this->tableColumns());
    }
    protected function tableFilters(): array
    {
        return [];
    }
    protected function tableActions(TableActionMenu $actions_menu): TableActionMenu
    {
        return $actions_menu;
    }

    public function getTableActions(): array
    {
        $_action_menu = new TableActionMenu();
        $action_menu = $this->tableActions($_action_menu);
        return $action_menu->render();
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
    public function setupCallbacks()
    {
        foreach ($this->formFields() as $key => $field) {
            if ($field instanceof FieldHasCallback) {
                $field->setupAjaxCallback();
            }
        }
    }

    public function getTableColumns($columns): array
    {
        return array_map(function ($column) {
            return [
                'label' => $column['label'],
                'field' => $column['field'],
                // 'file_path' => $column['file_path'],
                'type' => $column['type'],
                'align' => $column['align'],
                'sortable' => $column['sortable'],
                'ajax_call' => null,
                'searchable' => $column['searchable'],
            ];
        }, $columns);
    }
    public function getResourceTableData($pagination, $cols): array
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
    public function setupDataQuery(Builder $query, $columns)
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

    public function getResourceRoutes()
    {
        $route = $this->getRoute('name');
        return [
            "create" => $route . '.create',
            "edit" => $route . '.edit',
            "update" => $route . '.update',
            "store" => $route . '.store',
            "delete" => $route . '.delete',
            "index" => $route . '.index',
            "it-update" => $route . '.it-update',
        ];
    }
    public function getLabels()
    {

        return [
            'label' => $this->getLabel(),
            'plural_label' => $this->getPluralLabel(),
            'delete_confirmation_title' => $this->tr($this->short_label, 'resource.delete_confirmation_title'),
            'delete_confirmation_body' => $this->tr($this->short_label, 'resource.delete_confirmation_body'),
        ];
    }
    public function getFormTitles($action = 'create')
    {
        return $action == 'create' ? [
            'title' => $this->getCreatePageTitle(),
            'submit_button' => $this->tr($this->short_label, 'create'),
            'submit_button_and_create' => $this->tr($this->short_label, 'create_and_create_another'),
            'cancel_button' => $this->tr($this->short_label, 'cancel'),
        ] : [
            'title' => $this->getEditPageTitle(),
            // 'submit_button' => 'Update ' . $this->short_label,
            'submit_button' => $this->tr($this->short_label, 'update'),
            'delete_button' => $this->tr($this->short_label, 'delete'),
            'cancel_button' => $this->tr($this->short_label, 'cancel'),
        ];
    }


    protected function withTransaction(callable $callback, callable $onCommit = null, callable $onError = null)
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
            return $callback();
        }
    }
    private function buildModelData(Request $request, LVPAction $action = LVPAction::CREATE, array $old_data = []): array
    {
        $model_data = [];

        /**
         * @var FormField[] $fields
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

        /**
         * @var BackendField[] $backend_fields
         */

        $backend_fields = $this->backendFields();
        foreach ($backend_fields as $key => $field) {
            if ($field->canFillOn($action)) {
                $model_data[$field->field()] = $field->getValue();
            }
        }
        return $model_data;
    }
    // hooks
    public function beforeStoreModel(array $formData, Request $request): array
    {
        return $formData;
    }

    protected function afterStoreModel(Model $model, array $formData, Request $request)
    {
    }
    protected function onStoreModelFail(\Exception $exception, array $formData, Request $request)
    {
    }
    public function beforeUpdateModel(Model $model, array $formData, Request $request): array
    {
        return $formData;
    }

    protected function afterUpdateModel(Model $model, array $formData, Request $request)
    {
    }
    protected function onUpdateModelFail(\Exception $exception, Model $model, array $formData, Request $request)
    {
    }
    protected function afterCreateModelTranction(Model $model, Request $request)
    {
    }
    // full_path | name | path
    public function getRoute($type = 'name')
    {
        if ($type == 'name') {
            return $this->_panel->getRouteName() . '.' . $this->route_name;
        } else if ($type == 'full_path') {
            return url($this->_panel->getRouteName() . '/' . $this->route_path);
        } else {
            return $this->_panel->getRouteName() . '/' . $this->route_path;
        }
    }
    public function getMenuLabel()
    {
        return $this->navigation_label;
    }
    public function getNavMenu()
    {
        return [
            'label' => $this->getMenuLabel(),
            'icon' => $this->getMenuIcon(),
            'position' => $this->menu_position,
            'path' => $this->getRoutePath(),
        ];
    }
    public function getMenuGroup(): null|string
    {
        return $this->menu_group ?? null;
    }
    public function getMenuIcon(): string
    {
        return $this->menu_icon;
    }
    public function getRoutePath()
    {
        return $this->getRoute('full_path');
    }
    public function makeNames()
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
        $this->short_label = str($this->short_label)->lower();

        if ($this->show_on_navigation) {
            $this->navigation_label = $this->label;
        }
        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->label);

        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->label);
        $this->slug = str(!empty($this->slug) ? $this->slug : $class_base_name_plural)->kebab()->lower();
        $this->route_name = str(!empty($this->route_name) ? $this->route_name : $class_base_name_plural)->kebab()->lower();
        $this->route_path = str(!empty($this->route_path) ? $this->route_path : $class_base_name_plural)->kebab()->lower();
    }

    public static function getInstance(Panel $panel)
    {
        if (empty(static::$_instance)) {
            static::$_instance = new static($panel);
        }
        return static::$_instance;
    }

    public function getSearchableFields()
    {
        $fields = [];
        foreach ($this->tableColumns() as $key => $field) {
            if ($field->isSearchable()) {
                $fields[] = $field->field();
            }
        }
        return $fields;
    }

    protected function gridColumns()
    {
        return [];
    }
    protected function dataActions()
    {
        return [];
    }
    protected function dataGroupActions()
    {
        return [];
    }
    /**
     * Summary of dataFilters
     * @return DataFilterField[]
     */
    protected function dataFilters(): array
    {
        return [];
    }
    public function applyQueryFilter(Builder $query, array $request_filter)
    {
    }
    public function actionMenuType(string $type)
    {
        $this->_action_type = $type;
        return $this;
    }
    public function groupActionMenuType(string $type)
    {
        $this->_group_action_type = $type;
        return $this;
    }

    private function renderWidgets(array $widgets)
    {
        $_widgets = [];
        foreach ($widgets as $key => $_widget) {
            $_widgets[] = $_widget->render();
        }
        return $_widgets;
    }

    protected function beforeSendData(Request $request, array $data): array
    {
        return $data;
    }

    private function loadField(array $data, string $field = null)
    {

        return array_map(function ($it) use ($field) {
            if ($field) {
                return $it[$field];
            }
            return $it;
        }, $data);
    }

    public function makeRoutes()
    {
        Route::group(['prefix' => $this->route_path, 'as' => $this->route_name . '.'], function () {
            Route::get('/', function (Request $request) {

                return $this->index($request);
            })->name('index');
            if (!$this->modal_form) {
                Route::get('/create', function (Request $request) {
                    return $this->create($request);
                })->name('create');
                Route::get('/{id}/edit', function (Request $request, $id) {
                    return $this->edit($request, $id);
                })->name('edit');
            }
            Route::post('/store', function (Request $request) {
                return $this->store($request);
            })->name('store');
            Route::post('/it-update', function (Request $request) {
                return $this->itUpdate($request);
            })->name('it-update');
            Route::post('/update', function (Request $request) {
                return $this->update($request);
            })->name('update');
            Route::delete('/', function (Request $request) {
                return $this->destroy($request);
            })->name('delete');
        });
    }
    public function index(Request $request)
    {
        /**
         * @var  Builder  $query
         */


        // dd($this->_translations, $this->_locale, 'ddd');

        // dd($request->all());
        $titles = $this->getLabels();
        if ($this->modal_form == 'modal') {
            $titles['form_titles'] = [
                'edit' => $this->getFormTitles('edit'),
                'create' => $this->getFormTitles('create'),
            ];
        }
        $titles['create_resource'] = $this->getCreatePageTitle();
        $titles['edit_resource'] = $this->getEditPageTitle();
        $titles['index_page_title'] = $this->getIndexPageTitle();

        $data_filters = $this->dataFilters();
        /**
         * @var Builder
         */
        $query = $this->_model::query();
        if ($request->has('search')) {
            $searchable_columns = $this->getSearchableFields();
            if (!empty($searchable_columns)) {
                $query->whereAny($searchable_columns, 'LIKE', $request->get('search') . '%');
            }
        }
        $request_array_data = $request->toArray();
        // dd($data_filters);
        for ($i = 0; $i < count($data_filters); $i++) {
            $data_filters[$i]->apply($query, $request_array_data);
        }

        $this->applyQueryFilter($query, $request_array_data);
        $columns = $this->loadTableColumns();
        $this->setupDataQuery($query, $columns);
        $pagination = $query->with($this->_model_with)->paginate($request->input('perPage') ?? 10);
        $data = $this->getResourceTableData($pagination, $columns);
        // dd($data);
        $table_columns = $this->getTableColumns($columns);
        $table_columns[] = $this->getTableActionsColumn();
        $resources_routes = $this->getResourceRoutes();
        $form_type = $this->modal_form ? 'modal' : 'page';
        $table_actions = $this->getTableActions();
        $form_fields = $this->loadFormFields('create');
        $widgets = [];

        if ($this->_data_widget_type == 'table') {
            $data_widget = new DataTable($data);
            $data_widget->actionMenuType($this->_action_type)->groupActionMenuType($this->_group_action_type)->dataColumns($this->tableColumns())
                ->paginated()
                ->actions($this->dataActions())
                ->actionsGroup($this->dataGroupActions());
            if (count($data_filters) > 0) {
                $data_widget->filter($data_filters);
            }

            $widgets[] = [
                'type' => 'data_table',
                'data' => $data_widget->render(),
            ];
        } else if ($this->_data_widget_type == 'grid') {
            $data_widget = new DataGrid($data);
            $data_widget->actionMenuType($this->_action_type)->groupActionMenuType($this->_group_action_type)->dataColumns($this->tableColumns())
                ->paginated()
                ->actions($this->dataActions())
                ->actionsGroup($this->dataGroupActions());
            if (count($data_filters) > 0) {
                $data_widget->filter($data_filters);
            }

            $widgets[] = [
                'type' => 'data_grid',
                'data' => $data_widget->render(),
            ];
        }
        $before_data_widgets = $this->renderWidgets($this->beforeDataWidgets());
        $after_data_widgets = $this->renderWidgets($this->afterDataWidgets());
        $resource_data = $this->beforeSendData($request, compact('titles', 'resources_routes', 'widgets', 'form_type', 'form_fields', 'before_data_widgets', 'after_data_widgets'));
        // dd($resource_data);
        return Inertia::render('LVP/ResourcesIndex', $resource_data);
    }

    public function create(Request $request, $id = null)
    {
        $action = 'create';

        $_fields = $this->loadFormFields($action);

        $fields = $_fields['form_render'];
        $form_data = $_fields['form_data'];
        $resources_routes = $this->getResourceRoutes();
        $resourceId = [];
        $resource_data = [];
        $titles = $this->getLabels();
        $titles['form_titles'] = [
            'create' => $this->getFormTitles('create')
        ];

        // dd($resources_routes);
        return Inertia::render('LVP/ResourcesForm', compact('resourceId', "resource_data", "titles", 'fields', 'form_data', 'resources_routes', 'action'));
    }


    public function store(Request $request)
    {
        $valiator = Validator::make($request->all(), $this->getFieldRuls('create'));
        if ($valiator->fails()) {
            return redirect()->back()->withErrors($valiator->errors()->toArray())->with('error', 'Please check fields before submit');
        }
        $_formData = $this->buildModelData($request, LVPAction::CREATE);
        $formData = $this->beforeStoreModel($_formData, $request);
        return $this->withTransaction(
            function () use ($request, $formData) {
                $model = $this->_model::create($formData);
                $this->afterStoreModel($model, $formData, $request);
            },
            function () use ($request, $formData) {
                if ($request->input('after_save') === 'reload') {
                    return redirect()->back()->with('success', 'Created successfully');
                } else {
                    return to_route($this->getRoute('name') . '.index')->with('success', 'Created successfully');
                }
            },
            function ($exception) use ($request, $formData) {
                dd($exception);
                $this->onStoreModelFail($exception, $formData, $request);
                return redirect()->back()->with('error', 'Something went wrong');
            }
        );
    }
    public function edit(Request $request, $id = null)
    {
        $action = 'edit';
        // dd((new $this->_model)->getKeyName());
        $model_data = $this->_model::where($this->_model_primary_key, $id)->with($this->_model_with)->firstOrFail()->append($this->_model_appends);

        // dd($model_data->toArray());
        $_fields = $this->loadUpdateFormFields($model_data->toArray());
        // dd($_fields);
        $fields = $_fields['form_render'];
        $form_data = $_fields['form_data'];

        $form_data['id'] = $model_data[$this->_model_primary_key];
        $resourceId = [];
        $links = $this->getResourceRoutes();
        $titles = $this->getLabels();
        $titles['delete_button'] = 'Delete';
        $titles['form_titles'] = [
            'edit' => $this->getFormTitles('edit')
        ];
        $resources_routes = $this->getResourceRoutes();

        return Inertia::render('LVP/ResourcesForm', compact('resources_routes', 'resourceId', "titles", 'fields', 'form_data', 'resources_routes', 'action'));
    }
    public function update(Request $request)
    {
        // dd($request);
        $valiator = Validator::make(
            $request->all(),
            $this->getFieldRuls('edit')
        );

        if ($valiator->fails()) {
            return redirect()->back()->withErrors($valiator->errors()->toArray())->with('error', 'Please check fields before submit');
        }
        /**
         * @var Model $model
         */

        $model = $this->_model::where($this->_model_primary_key, $request->input('id'))->with($this->_model_with)->first();
        // dd($model, $request->input('id'));
        $_formData = $this->buildModelData($request, LVPAction::EDIT, $model->toArray());
        $formData = $this->beforeUpdateModel($model, $_formData, $request);

        return $this->withTransaction(
            function () use ($request, $formData, $model) {
                $model->update($formData);
                $this->afterUpdateModel($model, $formData, $request);
            },
            function () use ($request, $formData) {
                return redirect()->back()->with('success', 'Updated successfully');
            },
            function (\Exception $exception) use ($request, $formData, $model) {
                $this->onUpdateModelFail($exception, $model, $formData, $request);
                return redirect()->back()->with('error', 'Something went wrong. errors :' . $exception->getMessage());
            }
        );
    }
    public function itUpdate(Request $request)
    {

        /**
         * @var Model $model
         */
        $this->onItUpdate($request);
        $model = $this->_model::where($this->_model_primary_key, $request->input('resource_id'))->first();
        $formData = [$request->field => $request->value];
        return $this->withTransaction(
            function () use ($request, $formData, $model) {
                $formData = $this->beforeItUpdate($model, $formData, $request);
                $model->update($formData);
                $this->afterItUpdate($model, $formData, $request);
            },
            function () use ($request, $formData) {
                return redirect()->back()->with('success', $this->getLabel() . ' updated successfully');
            },
            function (\Exception $exception) use ($request, $formData, $model) {
                $this->onItUpdateFail($exception, $model, $formData, $request);
                return redirect()->back()->with('error', 'Something went wrong. errors :' . $exception->getMessage());
            }
        );
    }

    protected function beforeItUpdate(Model $model, array $formData, Request $request)
    {
        return $formData;
    }
    protected function afterItUpdate($model, array $formData, Request $request)
    {
    }
    protected function onItUpdate(Request $request)
    {
    }
    protected function onItUpdateFail(\Exception $exception, $model, array $formData, Request $request)
    {
    }

    public function destroy(Request $request)
    {
        $route = $this->getRoute('name') . '.index';
        $this->_model::destroy($request->id);
        return redirect()->route($route)->with('success', 'Deleted successfully');
    }
}
