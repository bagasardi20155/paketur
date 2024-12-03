<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UpdateManagerRequest;
use App\Http\Resources\ManagerResource;
use App\Http\Resources\StaffResource;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ManagerController extends Controller
{
    private array $allowedField = [
        'id', 'name', 'email', 'staff.id', 'staff.address', 'staff.hp', 'company.id', 'company.name', 'company.email', 'company.phone', 'address', 'hp', 'created_at', 'updated_at', 'deleted_at',
    ];
    private array $allowedFilter = [
        'name'
    ];

    public function index() {
        $manager = QueryBuilder::for(User::class)->whereHas(
            'roles', function ($query) {
                $query->where('name', 'manager');
            }
        )
            ->allowedFields($this->allowedField)
            ->allowedFilters($this->allowedFilter)
            ->allowedSorts($this->allowedField)
            ->allowedIncludes(['staff'])
            ->jsonPaginate();
        return UserResource::collection($manager);
    }

    public function show($id) {
        $manager = QueryBuilder::for(Staff::class)->where('id', $id)
            ->allowedFields(['id', 'address', 'hp', 'user_id', 'company_id', 'created_at', 'updated_at', 'deleted_at'])
            ->first();
        if ($manager) {
            return response()->json([
                'data' => new StaffResource($manager),
                'message' => 'Manager Loaded Successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'data' => [],
                'message' => "Manager Not Found",
                'success' => false,
            ]);
        }
    }

    public function update(UpdateManagerRequest $request, $id) {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();

        $manager = Staff::find($id);

        if ($manager) {
            if ($manager->user_id == auth()->user()->id) {
                $manager->update($validatedData);
                return response()->json([
                    'data' => new StaffResource($manager),
                    'message' => 'Manager Updated Successfully',
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'data' => [],
                    'message' => 'unauthorized',
                    'success' => false
                ]);
            }
        } else {
            return response()->json([
                'data' => [],
                'message' => "Manager Not Found",
                'success' => false,
            ]);
        }
    }
}
