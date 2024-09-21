<?php

namespace LVP\Modules\Resource\Features;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use LVP\Enums\LVPAction;
use LVP\Utils\CreateLVPAction;

trait Http
{

    private function index(Request $request)
    {
        $props = [
            'page_titles' => $this->getPageTitles(),
            'labels' => $this->getLabels(),
            'modal_labels' => $this->getModalLabels(),
            'routes' => $this->getRoutes(),
            'modal_form' => $this->getModalForm(),
            'form_type' => $this->getFormType(),
            'before_data_widgets' => $this->buildBeforeDataWidget($request),
            'data_widget' => $this->buildDataWidget($request),
            'after_data_widgets' => $this->buildAfterDataWidget($request),
        ];
        return Inertia::render('LVP/ResourcePage', $props);
    }
    private function show(Request $request, $id = null)
    {
        $model_infos = $this->model::where($this->model_primary_key, $id)->with($this->model_with)->first();
        $model_infos->append($this->model_appends);
        $props = [
            'model_infos' => $model_infos,
            'page_titles' => $this->getPageTitles(),
            'labels' => $this->getLabels(),
            'routes' => $this->getRoutes(),
            'widgets' => $this->buildInfoWidgets($model_infos->toArray()),
            'before_data_widgets' => $this->buildBeforeDataWidget($request),
            'after_data_widgets' => $this->buildAfterDataWidget($request),
        ];
        return Inertia::render('LVP/ResourceInfo', $props);

    }
    private function create()
    {
        $props = [
            'page_titles' => $this->getFormPageTitle(),
            'labels' => [
                'submit' => 'Create',
                'submit_and_create' => 'Create and Create Another',
                'cancel' => 'Cancel',
            ],
            'dialog' => [],
            'action' => 'create',
            'routes' => [
                'submit' => $this->getRoutes('store'),
                'cancel' => $this->getRoutes('index'),
                'index' => $this->getRoutes('index'),
            ],
            'form_component' => $this->buildCreateFormComponent(),
        ];
        return Inertia::render('LVP/ResourceForm', $props);
    }
    private function edit(Request $request, $id = null)
    {
        $resource = $this->model::where($this->model_primary_key, $id)->firstOrFail();
        $formDefaultData = $resource->toArray();
        $props = [
            'page_titles' => $this->getFormPageTitle('update'),
            'labels' => [
                'submit' => 'Create',
                'submit_and_create' => 'Create and Create Another',
                'cancel' => 'Cancel',
            ],
            'dialog' => [],
            'action' => 'update',
            'routes' => [
                'submit' => $this->getRoutes('update'),
                'cancel' => $this->getRoutes('index'),
                'index' => $this->getRoutes('index'),
            ],
            'form_component' => $this->buildEditFormComponent($formDefaultData),
        ];
        return Inertia::render('LVP/ResourceForm', $props);
    }
    private function store(Request $request)
    {
        $valiator = Validator::make($request->all(), $this->getFieldRuls('create'));
        if ($valiator->fails()) {
            return redirect()->back()->withErrors($valiator->errors()->toArray())->with('error', 'Please check fields before submit');
        }
        $_formData = $this->buildModelData($request, LVPAction::CREATE);
        $formData = $this->beforeStoreModel($_formData, $request);
        return $this->withTransaction(
            function () use ($request, $formData) {

                $model = $this->model::create($formData);
                $this->afterStoreModel($model, $formData, $request);
            },
            function () use ($request, $formData) {
                if ($request->input('after_save') === 'reload') {
                    return redirect()->back()->with('success', 'Created successfully');
                } else {
                    return to_route($this->getRoutes('index'))->with('success', 'Created successfully');
                }
            },
            function ($exception) use ($request, $formData) {
                dd($exception);
                $this->onStoreModelFail($exception, $formData, $request);
                return redirect()->back()->with('error', 'Something went wrong');
            }
        );
    }
    private function update(Request $request)
    {
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

        $model = $this->model::where($this->model_primary_key, $request->input('id'))->with($this->model_with)->first();
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
    protected function actions(): array
    {
        return [

        ];
    }
    protected function buildInActions(): array
    {
        return [
            'update_col' => CreateLVPAction::make('update_col', function ($model, $request) {
                $model::where($this->model_primary_key, $request->item_id)->first($this->model_primary_key)->update([
                    $request->field => $request->value
                ]);
            }, "Item updated", "Item update failded"),

        ];
    }
    private function execActions(Request $request)
    {
        /**
         * @var Model $model
         */
        $actions = [...$this->buildInActions(), ...$this->actions()];
        $action = $actions[$request->action];

        try {
            $action->exec($this->model, $request);
            return back()->with('success', $action->on_success_message);
        } catch (\Throwable $th) {
            dd($th);
            return back()->with('error', $action->on_fail_message);
        }

    }
    private function delete(Request $request)
    {
        $items = explode(',', $request->input('id'));
        $ids = $this->beforeDeleteModel($items, $request);

        return $this->withTransaction(
            function () use ($ids) {
                $this->model::whereIn($this->model_primary_key, $ids)->delete();
            },
            function () use ($ids, $request) {
                $this->afterDeleteModel($ids, $request);

                if ($request->has('redirect_to')) {
                    session()->flash('success', 'Deleted successfully');
                    return to_route($request->input('redirect_to'));
                } else {
                    return redirect()->back()->with('success', 'Deleted successfully');
                }
            },
            function (\Exception $exception) use ($ids, $request) {
                $this->onDeleteModelFail($exception, $ids, $request);
                return redirect()->back()->with('error', 'Something went wrong. errors :' . $exception->getMessage());
            }
        );
    }
}