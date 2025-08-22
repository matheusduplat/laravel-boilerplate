<?php

namespace App\Domains\State\Http\Controller;

use App\Domains\State\Http\Actions\StateAction;
use App\Domains\State\Http\Requests\ComponentSelectStateRequest;
use App\Domains\State\Http\Resources\ComponentSelectStateResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function componentSelect(ComponentSelectStateRequest $request, StateAction $stateAction)
    {
        $states = $stateAction->query($request->validated(), false);
        $states =  ComponentSelectStateResource::collection($states);
        return response()->json($states);
    }
}
