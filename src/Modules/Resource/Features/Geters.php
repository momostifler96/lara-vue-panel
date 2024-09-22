<?php

namespace LVP\Modules\Resource\Features;

use LVP\Actions\ButtonAction;
use LVP\Enums\HttpMethod;
use LVP\Widgets\FormWidget\FormWidget;

trait Geters
{

    #Geters
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
    public function getMenuLabel()
    {
        return $this->menu_label;
    }
    public function getNavMenu()
    {
        return [
            'label' => $this->getMenuLabel(),
            'position' => $this->menu_position,
            'icon' => $this->menu_icon,
            'path' => '/' . $this->slug,
        ];
    }
    public function getMenuGroup(): null|string
    {
        return $this->menu_group ?? null;
    }
    public function getMenuIcon()
    {
        return $this->menu_icon;
    }

    public function currentUser(): \Illuminate\Foundation\Auth\User|null
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
    public function getRoutePath()
    {
        return $this->geFullRoutepath('full_path');
    }
    public function getBaseRoute($type = 'full_path')
    {
        return $type == 'full_path' ? $this->slug : $this->parent_route_name . '.' . $this->route_name;
    }
    public function geFullRoutepath($type = 'full_path')
    {
        return $type == 'full_path' ? '/' . $this->parent_route_path . '/' . $this->slug : '/' . $this->parent_route_path . '.' . $this->route_name;
    }
    public function getCurrentClassNamespace()
    {
        return (new \ReflectionClass($this))->getNamespaceName();
    }
    public function canShowMenu(): bool
    {
        return $this->show_in_menu;
    }
    public function buildCreateFormComponent($action = 'create'): array
    {
        $form = FormWidget::make()->cols($this->form_grid_cols)->fields($this->formFields())->submitBtnLabel($this->tr($this->short_label, 'create'))->isCard(false)->method(HttpMethod::POST)->action(route($this->getRoutes($action == 'create' ? 'store' : 'update')))->render();
        return $form;
    }
    public function buildEditFormComponent($data): array
    {
        $form = FormWidget::make()->defaultData($data)->cols($this->form_grid_cols)->fields($this->formFields())->submitBtnLabel($this->tr($this->short_label, 'update'))->isCard(false)->method(HttpMethod::POST)->action(route($this->getRoutes('update')))->render();
        return $form;
    }
    public function buildFormFields(): array
    {
        return FormWidget::make()->cols($this->form_grid_cols)->colSpan($this->form_grid_gap)->fields($this->formFields())->submitBtnLabel($this->tr($this->short_label, 'create'))->isCard(false)->method(HttpMethod::POST)->action(route($this->getRoutes('create')))->render();
    }


    public function getIndexPageTitles()
    {
        return [
            'title' => $this->label,
            'meta_title' => $this->label,
            'meta_description' => $this->label,
        ];
    }
    public function getLabels()
    {
        return [
            'create' => $this->getCreatePageTitle(),
            'edit' => $this->getEditPageTitle(),
            'index' => $this->getIndexPageTitle(),
            'info' => 'Info ' . $this->label,
        ];
    }
    public function getModalLabels()
    {
        return [
            'create' => [
                'title' => $this->getCreatePageTitle(),
                'submit' => $this->getCreatePageTitle(),
                'cancel' => 'Cancel',
            ],
            'edit' => [
                'title' => $this->getEditPageTitle(),
                'submit' => $this->getCreatePageTitle(),
                'cancel' => 'Cancel',
            ],
            'delete' => [
                'title' => 'Delete',
                'message' => 'Delete',
                'confirm' => 'Delete',
                'cancel' => 'Cancel',
            ],
        ];
    }
    public function getResourceRoutess()
    {
        return [
            "create" => $this->route_name . '.create',
            "edit" => $this->route_name . '.edit',
            "update" => $this->route_name . '.update',
            "store" => $this->route_name . '.store',
            "delete" => $this->route_name . '.delete',
            "index" => $this->route_name . '.index',
            "show" => $this->route_name . '.show',
            "it-update" => $this->route_name . '.it-update',
        ];
    }

    public function getFormPageTitle($action = 'create')
    {
        return $action == 'create' ? [
            'title' => $this->getCreatePageTitle(),
            'submit' => $this->tr($this->short_label, 'create'),
            'submit_and_create' => $this->tr($this->short_label, 'create_and_create_another'),
            'cancel' => $this->tr($this->short_label, 'cancel'),
        ] : [
            'title' => $this->getEditPageTitle(),
            'submit' => $this->tr($this->short_label, 'update'),
            'delete' => $this->tr($this->short_label, 'delete'),
            'cancel' => $this->tr($this->short_label, 'cancel'),
        ];
    }

    public function getRoutes($route = 'all')
    {
        $routes = [
            'create' => $this->route_name . '.create',
            'edit' => $this->route_name . '.edit',
            'store' => $this->route_name . '.store',
            'update' => $this->route_name . '.update',
            'index' => $this->route_name . '.index',
            'delete' => $this->route_name . '.delete',
            "show" => $this->route_name . '.show',
            "exec_actions" => $this->route_name . '.exec-actions',

        ];

        if ($route == 'all') {
            return $routes;
        } else {
            return $routes[$route];
        }
    }
    public function getFormType()
    {
        return $this->form_type;
    }
    public function getModalForm()
    {
        return [
            'routes' => [
                'create' => $this->getRoutes('store'),
                'edit' => $this->getRoutes('update'),
            ],
            'titles' => [
                'create' => $this->getFormPageTitle('create'),
                'edit' => $this->getFormPageTitle('edit'),
            ],
            'fields' => $this->buildFormFields(),
        ];
    }

}