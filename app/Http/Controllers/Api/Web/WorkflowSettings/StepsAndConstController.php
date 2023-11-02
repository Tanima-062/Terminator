<?php

namespace App\Http\Controllers\Api\Web\WorkflowSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkflowSettings\SaveRequest;
use App\Models\WorkflowSetting;

class StepsAndConstController extends Controller
{

    public function show()
    {
        return WorkflowSetting::first();
    }

    public function save(SaveRequest $request)
    {
        $existing_settings = WorkflowSetting::first();
        if ($existing_settings)
            $existing_settings->update($request->validated());
        else
            $existing_settings = WorkflowSetting::create($request->validated());

        return $existing_settings;
    }
}
