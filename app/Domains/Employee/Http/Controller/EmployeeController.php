<?php

namespace App\Domains\Employee\Http\Controller;

use App\Domains\Employee\Http\Actions\DeleteEmployeeAction;
use App\Domains\Employee\Http\Actions\EmployeeAction;
use App\Domains\Employee\Http\Actions\RestoreEmployeeAction;
use App\Domains\Employee\Http\Actions\StoreEmployeeAction;
use App\Domains\Employee\Http\Actions\UpdateEmployeeAction;
use App\Domains\Employee\Http\Requests\ComponentSelectEmployeeRequest;
use App\Domains\Employee\Http\Requests\DeleteEmployeeRequest;
use App\Domains\Employee\Http\Requests\EmployeeRequest;
use App\Domains\Employee\Http\Requests\PerfilEmployeeRequest;
use App\Domains\Employee\Http\Requests\ShowEmployeeRequest;
use App\Domains\Employee\Http\Requests\StoreEmployeeRequest;
use App\Domains\Employee\Http\Requests\UpdateEmployeeRequest;
use App\Domains\Employee\Http\Resources\ComponentSelectEmployeeResource;
use App\Domains\Employee\Http\Resources\EmployeeResource;
use App\Domains\Employee\Model\Employee;
use App\Domains\Employee\Requests\RestoreEmployeeRequest;
use App\Domains\Phone\Http\Actions\DeletePhoneAction;
use App\Domains\Phone\Http\Actions\RestorePhoneAction;
use App\Domains\Phone\Http\Actions\StorePhoneAction;
use App\Domains\Phone\Http\Actions\UpdatePhoneAction;
use App\Domains\User\Http\Actions\DeleteUserAction;
use App\Domains\User\Http\Actions\RestoreUserAction;
use App\Domains\User\Http\Actions\StoreUserAction;
use App\Domains\User\Http\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Employee 
     * 
     * Traz todos os funcionários cadastrados
     * 
     * 
     */
    public function index(EmployeeRequest $request, EmployeeAction $employeeAction)
    {
        $employees = $employeeAction->execute($request->validated(), false);
        return response()->json($employees);
    }
    /**
     * Employee with pagination
     * 
     * Traz todos os funcionários cadastrados com paginação 
     * 
     * 
     */
    public function withPagination(EmployeeRequest $request, EmployeeAction $employeeAction)
    {
        $employees = $employeeAction->execute($request->validated(), true);
        return response()->json($employees);
    }

    /**
     * Employee store
     * 
     * Cria um novo funcionário
     */
    public function store(StoreEmployeeRequest $request, StoreEmployeeAction $storeEmployeeAction, StoreUserAction $storeUserAction, StorePhoneAction $storePhoneAction)
    {
        $data = $request->validated();

        $employee =  DB::transaction(function () use ($data, $storeUserAction, $storeEmployeeAction, $storePhoneAction) {
            $employee = $storeEmployeeAction->execute($data);
            $user = $storeUserAction->execute($data, $employee);
            $storePhoneAction->execute($data['phones'], $employee);
            return $employee;
        });

        return response()->json(['message' => __('Employee created successfully.')], 201);
    }

    /**
     * Employee show
     * 
     * Mostra os detalhes de um funcionário
     */
    public function show(ShowEmployeeRequest $request, Employee $employee)
    {
        // $employee->relationLoadWithTrashed();
        $employee = new EmployeeResource($employee);
        return response()->json($employee);
    }

    /**
     * Employee update
     * 
     * Atualiza os detalhes de um funcionário
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee,
        UpdateEmployeeAction $updateEmployeeAction,
        UpdateUserAction $updateUserAction,
        UpdatePhoneAction  $updatePhoneAction,
        StorePhoneAction $storePhoneAction,
        DeletePhoneAction $deletePhoneAction
    ) {
        $data = $request->validated();

        DB::transaction(function () use ($data, $employee, $updateEmployeeAction, $updateUserAction, $updatePhoneAction, $storePhoneAction, $deletePhoneAction) {
            $updateEmployeeAction->execute($employee, $data);
            $updateUserAction->execute($employee->user, $data);

            $phonesEdit = collect($data['phones'])->filter(function ($phone) {
                return isset($phone['id']);
            });
            $phonesCreate = collect($data['phones'])->filter(function ($phone) {
                return !isset($phone['id']);
            });

            $updatePhoneAction->execute($phonesEdit, $employee);
            $storePhoneAction->execute($phonesCreate->toArray(), $employee);

            if (isset($data['delete_phones'])) {
                $deletePhoneAction->execute($data['delete_phones']);
            }
        });

        return response()->json(['message' => __('Employee updated successfully.')], 201);
    }

    /**
     * Employee destroy
     * 
     * Deleta um funcionário
     */
    public function destroy(DeleteEmployeeRequest $request, Employee $employee, DeleteEmployeeAction $deleteEmployeeAction, DeleteUserAction $deleteUserAction, DeletePhoneAction $deletePhoneAction)
    {
        DB::transaction(function () use ($employee, $deleteEmployeeAction, $deleteUserAction, $deletePhoneAction) {
            $deleteEmployeeAction->execute($employee);
            $deleteUserAction->execute($employee->user);
            $deletePhoneAction->execute($employee->phones->pluck('id')->toArray());
        });
        return response()->json(['message' => __('Employee deleted successfully.')], 201);
    }

    public function perfil(
        PerfilEmployeeRequest $request,
        Employee $employee,
        UpdateEmployeeAction $updateEmployeeAction,
        UpdateUserAction $updateUserAction,
        UpdatePhoneAction  $updatePhoneAction,
        StorePhoneAction $storePhoneAction,
        DeletePhoneAction $deletePhoneAction
    ) {
        $data = $request->validated();

        $user = Auth::user();
        if ($user->userable->id != $employee->id) {
            return response()->json(['message' => __('You cannot edit a profile that is not yours.')], 400);
        }


        DB::transaction(function () use ($data, $employee, $updateEmployeeAction, $updateUserAction, $updatePhoneAction, $storePhoneAction, $deletePhoneAction) {
            $updateEmployeeAction->execute($employee, $data);
            $updateUserAction->execute($employee->user, $data);

            $phonesEdit = collect($data['phones'])->filter(function ($phone) {
                return isset($phone['id']);
            });
            $phonesCreate = collect($data['phones'])->filter(function ($phone) {
                return !isset($phone['id']);
            });

            $updatePhoneAction->execute($phonesEdit, $employee);
            $storePhoneAction->execute($phonesCreate->toArray(), $employee);

            if (isset($data['delete_phones'])) {
                $deletePhoneAction->execute($data['delete_phones']);
            }
        });

        return response()->json(['message' => __('Profile updated successfully.')], 201);
    }
    public function restore(RestoreEmployeeRequest $request, Employee $employee, RestoreEmployeeAction $restoreEmployeeAction, RestorePhoneAction $restorePhoneAction, RestoreUserAction $restoreUserAction)
    {
        DB::transaction(function () use ($employee, $restoreEmployeeAction, $restorePhoneAction, $restoreUserAction) {
            $employee->relationLoadWithTrashed();
            $restoreEmployeeAction->execute($employee);
            $restoreUserAction->execute($employee->user);
            $restorePhoneAction->execute($employee->phones);
        });
        return response()->json(['message' => __('Employee restored successfully.')], 201);
    }
    public function componentSelect(ComponentSelectEmployeeRequest $request, EmployeeAction $employeeAction)
    {
        $data = $request->validated();
        $employees = $employeeAction->query($data, true);
        $employees = new ComponentSelectEmployeeResource($employees);
        return response()->json($employees);
    }
}
