<?php

namespace App\Domains\Role\Http\Controller;

use App\Domains\Role\Http\Actions\DeleteRoleAction;
use App\Domains\Role\Http\Actions\RestoreRoleAction;
use App\Domains\Role\Http\Actions\RoleAction;
use App\Domains\Role\Http\Actions\StoreRoleAction;
use App\Domains\Role\Http\Actions\UpdateRoleAction;
use App\Domains\Role\Http\Requests\ComponentSelectRoleRequest;
use App\Domains\Role\Http\Requests\DeleteRoleRequest;
use App\Domains\Role\Http\Requests\RoleRequest;
use App\Domains\Role\Http\Requests\ShowRoleRequest;
use App\Domains\Role\Http\Requests\StoreRoleRequest;
use App\Domains\Role\Http\Requests\UpdateRoleRequest;
use App\Domains\Role\Http\Resource\RoleComponentSelectResource;
use App\Domains\Role\Http\Resource\RoleResource;
use App\Domains\Role\Model\Role;
use App\Http\Controllers\Controller;


class RoleController extends Controller
{
    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Função com paginação
     * 
     * Todas as Funções paginada
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Http\Actions\RoleAction $roleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*******  4c4de094-497b-4a90-a6e6-6ac56fa3579a  *******/
    public function withPagination(RoleRequest $request, RoleAction $roleAction)
    {
        $data = $request->validated();
        $roles = $roleAction->execute($data, true);
        return response()->json($roles);
    }

    /**
     * Função componentSelect
     * 
     * Retorna todas as funções em formato de {label: '', value: ''} com paginação
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Http\Actions\RoleAction $roleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function componentSelect(ComponentSelectRoleRequest $request, RoleAction $roleAction)
    {
        $data = $request->validated();
        $roles = $roleAction->query($data, true);
        $roles = new RoleComponentSelectResource($roles);
        return response()->json($roles);
    }

    /**
     * Função index
     * 
     * Todas as Funções
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Http\Actions\RoleAction $roleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RoleRequest $request, RoleAction $roleAction)
    {
        $data = $request->validated();
        $roles = $roleAction->execute($data, false);
        return response()->json($roles);
    }

    /**
     * Função show
     * 
     * Mostra uma Função
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Model\Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRoleRequest $request, Role $role)
    {
        $role = new RoleResource($role);
        return response()->json($role);
    }

    /**
     * Função store
     * 
     * Cria uma Função
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Http\Actions\CreateRoleAction $createRoleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request, StoreRoleAction $storeRoleAction)
    {
        $data = $request->validated();
        $role = $storeRoleAction->execute($data);
        return response()->json(['message' => __('Role created successfully.')]);
    }

    /**
     * Função update
     * 
     * Atualiza uma Função
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Model\Role $role
     * @param \App\Domains\Role\Http\Actions\UpdateRoleAction $updateRoleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $updateRoleAction)
    {
        $data = $request->validated();
        $role = $updateRoleAction->execute($data, $role);
        return response()->json(['message' => __('Role updated successfully.')]);
    }

    /**
     * Função destroy
     * 
     * Deleta uma Função
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Model\Role $role
     * @param \App\Domains\Role\Http\Actions\DeleteRoleAction $deleteRoleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRoleRequest $request, Role $role, DeleteRoleAction $deleteRoleAction)
    {
        $role = $deleteRoleAction->execute($role);
        return response()->json(['message' => __('Role deleted successfully.')]);
    }

    /**
     * Função restore
     * 
     * Restaurar uma Função
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Domains\Role\Model\Role $role
     * @param \App\Domains\Role\Http\Actions\RestoreRoleAction $restoreRoleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(DeleteRoleRequest $request, Role $role, RestoreRoleAction $restoreRoleAction)
    {
        $role = $restoreRoleAction->execute($role);
        return response()->json(['message' => __('Role restored successfully.')]);
    }
}
