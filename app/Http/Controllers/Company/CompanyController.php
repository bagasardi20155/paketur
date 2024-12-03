<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Staff;
use App\Models\User;
use Spatie\Query;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    private array $company_field = [
		'id', 'name', 'email', 'phone', 'created_at', 'updated_at', 'deleted_at',
	];
    private array $allowedField = [
        'name', 'email', 'phone',
    ];
    private array $allowedFilter = [
        'name'
    ];

    public function index() {
        $company = QueryBuilder::for(Company::class)
            ->allowedFields($this->allowedField)
            ->allowedFilters($this->allowedFilter)
            ->allowedSorts($this->company_field)
            ->jsonPaginate();
        return CompanyResource::collection($company);
    }

    public function show($id) {
        $company = QueryBuilder::for(Company::class)->where('id', $id)
            ->allowedFields($this->allowedField)
            ->first();
        if ($company) {
            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company Loaded Successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'data' => [],
                'message' => "Company Not Found",
                'success' => false,
            ]);
        }
    }

    public function store(StoreCompanyRequest $request) {
        $validatedData = $request->validated();
        try {
            $company = Company::create($validatedData);

            $manager_account = User::create([
                'name' => "Manager " . $validatedData['name'],
                'email' => "manager_" . strtolower(str_replace(' ', '', $validatedData['name'])) . "@paketur.com",
                'password' => bcrypt('password'),
            ]);
            $manager_data = Staff::create([
                'user_id' => $manager_account->id,
                'company_id' => $company->id,
                'address' => '',
                'hp' => '',
            ]);
            $manager_account->assignRole('manager');

            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company Created Successfully',
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

    public function update(UpdateCompanyRequest $request, $id) {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();

        $company = Company::find($id);

        if ($company) {
            $company->update($validatedData);
            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company Updated Successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'data' => [],
                'message' => "Company Not FOund",
                'success' => false,
            ]);
        }
    }

    public function destroy($id) {
        $company = Company::find($id);
        if (auth()->user()->hasRole('superadministrator')) {
            if ($company) {
                $company->delete();
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
