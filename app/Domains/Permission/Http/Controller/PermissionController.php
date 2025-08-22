<?php

namespace App\Domains\Permission\Http\Controller;

use App\Domains\Permission\Http\Actions\PermissionAction;
use App\Domains\Permission\Http\Requests\ComponentSelectPermissionRequest;
use App\Domains\Permission\Http\Resources\PermissionComponentSelectResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function componentSelect(ComponentSelectPermissionRequest $request, PermissionAction  $permissionAction)
    {
        $data = $request->all();
        $permissions = $permissionAction->query($data, true);
        $permissions = new PermissionComponentSelectResource($permissions);
        return response()->json($permissions);
    }
}
