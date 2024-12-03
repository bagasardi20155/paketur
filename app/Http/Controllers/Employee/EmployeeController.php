<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\StaffResource;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeController extends Controller
{
    private array $allowedField = [
        'id', 'name', 'email', 'staff.id', 'staff.address', 'staff.hp', 'company.id', 'company.name', 'company.email', 'company.phone', 'address', 'hp', 'created_at', 'updated_at', 'deleted_at',
    ];
    private array $allowedFilter = [
        'name'
    ];

    public function index() {
        $employee = QueryBuilder::for(User::class)->whereHas(
            'roles', function ($query) {
                $query->where('name', 'employee');
            }
        )
            ->allowedFields($this->allowedField)
            ->allowedFilters($this->allowedFilter)
            ->allowedSorts($this->allowedField)
            ->allowedIncludes(['staff'])
            ->jsonPaginate();
        return UserResource::collection($employee);
    }

    public function show($id) {
        $employee = QueryBuilder::for(Staff::class)->where('id', $id)
            ->allowedFields(['id', 'address', 'hp', 'user_id', 'company_id', 'created_at', 'updated_at', 'deleted_at'])
            ->first();
        if ($employee) {
            return response()->json([
                'data' => new StaffResource($employee),
                'message' => 'Employee Loaded Successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'data' => [],
                'message' => "Employee Not Found",
                'success' => false,
            ]);
        }
    }

    public function store(StoreEmployeeRequest $request) {
        $validatedData = $request->validated();
        try {
            $employee_account = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);
            $employee_data = Staff::create([
                'user_id' => $employee_account->id,
                'company_id' => $validatedData['company_id'],
                'address' => $validatedData['address'],
                'hp' => $validatedData['hp'],
            ]);
            $employee_account->assignRole('employee');

            return response()->json([
                'data' => new StaffResource($employee_account),
                'message' => 'Employee Created Successfully',
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'message' => $th->getMessage(),
                'success' => false,
            ]);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id) {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();

        $employee = Staff::find($id);

        if ($employee) {
            $employee->update($validatedData);
            return response()->json([
                'data' => new StaffResource($employee),
                'message' => 'Employee Updated Successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'data' => [],
                'message' => "Employee Not Found",
                'success' => false,
            ]);
        }
    }

    public function destroy($id) {
        $employee = Staff::find($id);
        $user = $employee->user_id;

        if (auth()->user()->hasRole('manager')) {
            if ($employee) {
                $employee->delete();

                $delete_user = User::find($user);
                $delete_user->delete();
                
                return response()->json([
                    'data' => [],
                    'message' => 'Company Deleted Successfully',
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'data' => [],
                    'message' => "Company Not Found",
                    'success' => false,
                ]);
            }
        } else {
            return response()->json([
                'data' => [],
                'message' => "Unauthorized",
                'success' => false,
            ]);
        }
    }
}
