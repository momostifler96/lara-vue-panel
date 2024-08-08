<?php
namespace LVP\Modules\Resource\Features;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait Hooks
{
    //Store model hooks 
    protected function beforeStoreModel(array $formData, Request $request): array
    {
        return $formData;
    }
    protected function afterStoreModel(Model $model, array $formData, Request $request)
    {
    }
    protected function onStoreModelFail(\Exception $exception, array $formData, Request $request)
    {
    }

    //Update model hooks 
    protected function beforeUpdateModel(Model $model, array $formData, Request $request): array
    {
        return $formData;
    }
    protected function afterUpdateModel(Model $model, array $formData, Request $request)
    {
    }
    protected function onUpdateModelFail(\Exception $exception, Model $model, array $formData, Request $request)
    {
    }

    //Delete model hooks 
    protected function beforeDeleteModel(array $models_id, Request $request): array
    {
        return $models_id;
    }
    protected function afterDeleteModel(array $models_id, Request $request)
    {
    }
    protected function onDeleteModelFail(\Exception $exception, array $models_id, Request $request)
    {
    }
}
