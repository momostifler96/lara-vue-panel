<?php

namespace LVP\Modules\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use LVP\Enums\DataFilterType;
use LVP\Enums\LVPMenu;
use LVP\Modules\Resource\Features\Booting;
use LVP\Modules\Resource\Features\Hooks;
use LVP\Modules\Resource\Features\Http;
use LVP\Modules\Resource\Features\Geters;
use LVP\Modules\Resource\Features\Actions;
use LVP\Modules\Resource\Features\ResourceDataWidget;
use LVP\Widgets\DataWidgets\DataWidget;
use LVP\Widgets\LVPWidget;


class Resource
{
    use Geters, Booting, Http, Actions, Hooks;

    #Model settings
    protected string $model;
    protected array $model_appends = [];
    protected array $model_with = [];
    protected string $model_primary_key = 'id';

    #Resource settings
    protected string $local = 'en';
    protected string $gender = 'male';
    protected bool $enable_transaction = false;
    public bool $disabled = false;
    protected bool $show_in_menu = true;
    protected array $_translations = [];


    #Middlewares
    protected array $middlewares = [];
    protected array $index_middlewares = [];
    protected array $create_middlewares = [];
    protected array $show_middlewares = [];
    protected array $edit_middlewares = [];
    protected array $store_middlewares = [];
    protected array $update_middlewares = [];
    protected array $delete_middlewares = [];

    #Labels
    protected string $label;
    protected string $plural_label;
    protected string $short_label;
    protected string $one_label;
    protected string $menu_label;
    protected int $menu_position = 0;
    protected int $form_grid_cols = 1;
    protected int $form_grid_gap = 1;
    protected string $menu_icon = 'stack';
    protected string $menu_group;

    #Titles
    protected string $title;
    protected string $create_title;
    protected string $edit_title;

    #Routing infos
    protected string $slug;
    protected string $route_name;
    protected string $form_type = 'modal';


    protected LVPMenu $menu_type = LVPMenu::PRINCIPAL;
    protected DataFilterType $data_filter_type = DataFilterType::POPOVER;
    protected bool $auto_submit_filter = true;

    protected \Illuminate\Foundation\Auth\User|null $current_user;

    public function __construct()
    {
        $locale = config('app.locale');
        $this->locale = $locale;
        $tr = require __DIR__ . './../../Translations/' . $locale . '.php';
        $this->_translations = $tr;
    }

    #Custom widgets
    protected function beforeDataWidgets(): array
    {
        return [];
    }
    protected function afterDataWidgets(): array
    {
        return [];
    }

    protected function dataWidget(): DataWidget
    {
        return ResourceDataWidget::table()
            ->columns([

            ])->actions([

                ])->bulkActions([

                ])->filters([

                ]);
    }



    protected function infoList(): array
    {
        return [];
    }
    protected function formFields(): array
    {
        return [];
    }
    protected function dataActionsGroup(): array
    {
        return [];
    }




}