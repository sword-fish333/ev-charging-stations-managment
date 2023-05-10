<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::main()->with(['childCompanies','Stations'])->orderByDesc('id')->get();
        return \response()->json(['success' => true, 'companies' => $companies]);
    }

    public function childCompanies($company_id)
    {
        $companies = Company::where('parent_company_id', $company_id)->orderByDesc('id')->get();
        return \response()->json(['success' => true, 'child_companies' => $companies]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $validated = $request->validated();
        try {
            $company = Company::create($validated);
            return response()->json(['success' => true, 'message' => 'Company created successfully', 'company' => $company], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function update(StoreCompanyRequest $request, Company $company)
    {
        $validated = $request->validated();
        try {
            $company->update($validated);
            return response()->json(['success' => true, 'message' => 'Company updated successfully', 'company' => $company], Response::HTTP_OK);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function delete(Company $company)
    {
        try {
            $company->delete();
            return response()->json(['success' => true, 'message' => 'Company deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }


}
