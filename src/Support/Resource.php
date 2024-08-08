<?php

namespace LVP\Support;

use Illuminate\Auth\Authenticatable;
use LVP\Facades\TableActionMenu;
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
use LVP\Providers\PanelProvider;
use LVP\Widgets\DataWidgets\DataGrid;
use LVP\Widgets\DataWidgets\DataTable;
use LVP\Widgets\DataWidgets\DataWidget;
use LVP\Widgets\DataWidgets\Filters\DataFilterField;
use LVP\Widgets\LVPWidget;

class Resource
{
    #Model settings
    protected string $model = Model::class;
    protected array $model_appends = [];
    protected array $model_with = [];
    protected string $model_primary_key = 'id';

    #Resource settings
    protected string $_local = 'en';
    protected string $gender = 'male';
    public bool $disabled = false;

    #Routing infos
    protected string $slug;
    protected string $route_name;
    protected string $_panel_route_path;
    protected string $_panel_route_name;
    protected \Illuminate\Foundation\Auth\User $current_user;

    #Labels
    protected string $label;
    protected bool $has_modal_form = false;
    protected string $short_label;
    protected string $nav_menu_label;
    protected string $plural_label;
    protected string $page_title;
    protected string $meta_title;
    protected string $index_page_title;
    protected string $create_page_title;
    protected string $edit_page_title;

    #Middlewares
    protected array $middlewares;
    protected array $index_middlewares;
    protected array $create_middlewares;
    protected array $edit_middlewares;
    protected array $_fields_rules;
    protected $_translations = [];
    protected $enable_transaction = true;
    protected string $menu_icon = 'stack';
    protected string $menu_group;
    public bool $show_on_navigation = true;
    protected string $navigation_label;
    protected int $menu_position = 0;

    #Data widge
    protected DataWidget $_data_widget;


    public function __construct()
    {
        $locale = config('app.locale');
        $this->_locale = $locale;
        $tr = require __DIR__ . './../Translations/' . $locale . '.php';
        $this->_translations = $tr;
    }

    #Geters
    public function getRoute($route = 'all'): array|string
    {
        $routes = [
            'index' => $this->slug . '.index',
            'store' => $this->slug . '.store',
            'create' => $this->slug . '.create',
            'edit' => $this->slug . '.edit',
            'update' => $this->slug . '.update',
            'delete' => $this->slug . '.delete',
        ];

        if ($route == 'all') {
            return $routes;
        } else {
            return $routes[$route];
        }
    }
    public function getNavMenu(): array|string
    {
        return [
            'label' => $this->navigation_label,
            'icon' => $this->menu_icon,
            'position' => $this->menu_position,
            'path' => url($this->slug),
        ];
    }
    public function getLabels(): array
    {
        return [
            'navigation' => $this->navigation_label,
            'index' => $this->label,
            'short' => $this->short_label,
            'plural' => $this->plural_label,
            'title' => $this->page_title,
            'delete_confirmation_title' => $this->tr($this->short_label, 'resource.delete_confirmation_title'),
            'delete_confirmation_body' => $this->tr($this->short_label, 'resource.delete_confirmation_body'),
        ];
    }
    public function getMenuGroup(): null|string
    {
        return $this->menu_group ?? null;
    }

    #Settings methods
    protected function setupPanel(PanelProvider $panel_provider)
    {
        $this->_panel_route_name = $panel_provider->getPanelRouteName();
        $this->_panel_route_path = $panel_provider->getPanelRoutePath();
        $this->_local = $panel_provider->getPanelLocal();
        $this->_middlewares = $panel_provider->getMiddlewares();
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
        if (empty($this->plural_label)) {
            $this->plural_label = $class_base_name_plural;
        }

        $this->short_label = str($this->short_label)->lower();

        if ($this->show_on_navigation) {
            $this->navigation_label = $this->label;
        }

        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->label);
        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->label);

        $this->slug = $this->_panel_route_path . '/' . str(!empty($this->slug) ? $this->slug : $class_base_name_plural)->kebab()->lower()->toString();
        $this->route_name = $this->_panel_route_name . '.' . str(!empty($this->route_name) ? $this->route_name : $class_base_name_plural)->kebab()->lower()->toString();
    }
    public function register(PanelProvider $panelProvider)
    {
        $this->setupPanel($panelProvider);
        $this->makeNames();
        $this->registerRoutes();
        // $this->setupPanel($panelProvider);
    }
    #-----------------—
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
    public function getIndexPageTitle()
    {
        return str(empty($this->index_page_title) ? $this->page_title : $this->index_page_title)->ucfirst();
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
        return $this->model;
    }
    public function getModelBaseName()
    {
        return str(class_basename($this->model))->kebab()->replace('-', ' ')->singular()->lower();
    }


    public function getEnableTranction()
    {
        return $this->enable_transaction;
    }
    #-----------------—

    public function currentUser(): \Illuminate\Foundation\Auth\User
    {
        if (empty($this->current_user)) {
            /**
             * @var \LVP\Providers\PanelProvider $current_panel
             */
            $current_panel = app('lvp-current');
            $this->current_user = auth($current_panel->getId())->user();
        }

        return $this->current_user;
    }



    protected function setAuthtUser(PanelProvider $panel_provider)
    {
        $panel_provider = app('lvp-current');
        $this->panel_provider = $panel_provider->user();
    }

    public function boot(PanelProvider $panel_provider)
    {

    }
    public function canShowInMenu(): bool
    {
        return $this->show_on_navigation;
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
    private function renderWidgets(array $widgets)
    {
        $_widgets = [];
        foreach ($widgets as $key => $_widget) {
            $_widgets[] = $_widget->render();
        }
        return $_widgets;
    }
    private function buildDataWidget(array $data, $data_filters)
    {
        $data_widget = new DataTable($data);
        $data_widget->actionMenuType(ActionMenuType::INLINE)->groupActionMenuType(ActionMenuType::DROPDOWN)->dataColumns($this->tableColumns())
            ->paginated()
            ->actions($this->dataActions())
            ->actionsGroup($this->dataGroupActions());

        if (count($data_filters) > 0) {
            $data_widget->filter($data_filters);
        }

        return [
            'type' => 'data_table',
            'data' => $data_widget->render(),
        ];
    }
    #Http routes

    public function registerRoutes()
    {

        Route::middleware($this->_middlewares)
            ->as($this->route_name . '.')
            ->group(function () {
                Route::get($this->slug . '/', fn(Request $request) => $this->index($request))->name('index');
                Route::get($this->slug . '/create', fn(Request $request) => $this->create($request))->name('create');
                Route::post($this->slug . '/store', fn(Request $request) => $this->store($request))->name('store');
                Route::get($this->slug . '/edit/{id}', fn(Request $request, $id) => $this->edit($request, $id))->name('edit');
                Route::post($this->slug . '/update/{id}', fn(Request $request, $id) => $this->update($request, $id))->name('update');
                Route::get($this->slug . '/show/{id}', fn(Request $request, $id) => $this->show($request, $id))->name('show');
                Route::delete($this->slug . '/destroy/{id}', fn(Request $request, $id) => $this->edit($request, $id))->name('destroy');
            });
    }

    public function getCurrentClassNamespace()
    {
        return (new \ReflectionClass($this))->getNamespaceName();
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
         * @var \LVP\Facades\FormField[] $fields
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
         * @var \LVP\Facades\BackendField[] $backend_fields
         */

        $backend_fields = $this->backendFields();
        foreach ($backend_fields as $key => $field) {
            if ($field->canFillOn($action)) {
                $model_data[$field->field()] = $field->getValue();
            }
        }
        return $model_data;
    }

    protected function dataFilters()
    {
        return [];
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
    protected function formFields()
    {
        return [];
    }
    protected function backendFields()
    {
        return [];
    }

    // hooks
    public function beforeStoreModel(array $formData, Request $request): array
    {
        return $formData;
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

    protected function beforeSendData(Request $request, array $props)
    {
        return $props;
    }

    public function applyQueryFilter(Builder $query, array $request_filter)
    {
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
    public function getTableActions(): array
    {
        // $_action_menu = new TableActionMenu();
        // $action_menu = $this->tableActions($_action_menu);
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
    #http controllers

    public function index(Request $request)
    {
        /**
         * @var  Builder  $query
         */
        $titles = $this->getIndexTitles();
        $data_filters = $this->dataFilters();
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
        $request_array_data = $request->toArray();
        // dd($data_filters);
        for ($i = 0; $i < count($data_filters); $i++) {
            $data_filters[$i]->apply($query, $request_array_data);
        }

        $this->applyQueryFilter($query, $request_array_data);
        $columns = $this->loadTableColumns();
        $this->setupDataQuery($query, $columns);
        $pagination = $query->with($this->model_with)->paginate($request->input('perPage') ?? 10);
        $data = $this->getResourceTableData($pagination, $columns);

        $table_columns = $this->getTableColumns($columns);
        $table_columns[] = $this->getTableActionsColumn();

        $resources_routes = $this->getResourceRoutes();
        $form_type = $this->getFormType();

        $table_actions = $this->getTableActions();
        $form_fields = $this->loadFormFields('create');

        $widgets = $this->buildDataWidget($data, $table_columns);
        $before_data_widgets = $this->renderWidgets($this->beforeDataWidgets());
        $after_data_widgets = $this->renderWidgets($this->afterDataWidgets());
        $props = $this->beforeSendData($request, compact('titles', 'resources_routes', 'widgets', 'form_type', 'form_fields', 'before_data_widgets', 'after_data_widgets'));
        dd($props);
        return Inertia::render('LVP/ResourcesPage', $props);
    }
    public function _index(Request $request)
    {

        $props = compact('');
        return Inertia::render('LVP/ResourcesPage', $props);
    }


    public function create(Request $request)
    {
        dd(__METHOD__, $this->_panel_route_path);
    }
    public function getIndexTitles()
    {
        $titles = $this->getLabels();
        if ($this->has_modal_form) {
            $titles['form_titles'] = [
                'edit' => $this->getFormTitles('edit'),
                'create' => $this->getFormTitles('create'),
            ];
        }
        $titles['create_resource'] = $this->getCreatePageTitle();
        $titles['edit_resource'] = $this->getEditPageTitle();
        $titles['index_page_title'] = $this->getIndexPageTitle();
        return $titles;
    }
    public function getFormType()
    {
        return 'modal';
    }
    public function show(Request $request, $id = null)
    {
        dd(__METHOD__, $this->_panel_route_path);

    }
    public function store(Request $request, $id = null)
    {
        dd(__METHOD__, $this->_panel_route_path);

    }
    public function edit(Request $request, $id = null)
    {
        dd(__METHOD__, $this->_panel_route_path);

    }
    public function update(Request $request, $id = null)
    {
        dd(__METHOD__, $this->_panel_route_path);

    }
    public function delete(Request $request, $id = null)
    {
        dd(__METHOD__, $this->_panel_route_path);
    }

}
