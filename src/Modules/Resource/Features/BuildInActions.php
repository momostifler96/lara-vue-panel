<?php
use Illuminate\Http\Request;

return [
    'change_toggle' => [
        'action' => function ($model, Request $request) {
            $model->update([
                $request->input('column') => $request->input('value'),
            ]);
        },
        'on_success_message' => "Item updated",
        'on_fail_message' => "Item update failed",
    ]
];