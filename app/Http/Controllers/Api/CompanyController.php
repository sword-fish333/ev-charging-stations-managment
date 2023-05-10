<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCompanyRequest;
use App\Models\Company;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function chargingStations(Company $company){
        $rules=[
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'=>'required|numeric|min:0|max:100'
        ];
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
           return response()->json(['success'=>false,'errors'=>$validator->errors()],Response::HTTP_BAD_REQUEST);
        }
        $company_ids= $company->childCompaniesIds();
        $company_ids->push($company->id);
        $haversine=$this->getHaversineFormula(request('latitude'),request('longitude'));
        $radius=request('radius');
        $stations=Station::whereIn('company_id',$company_ids)->selectRaw("*,
                                            {$haversine} AS distance")->whereRaw("$haversine<=$radius")->with('Company')->orderBy('distance')->get();
        $response=$this->formatStations($stations);
        return response()->json(['success' => true, 'message' => 'Available stations', 'data' => $response], Response::HTTP_OK);
    }

    private function formatStations($stations): \Illuminate\Support\Collection
    {
        $response_companies=collect();
        $stations->each(function ($station)use(&$response_companies){
            if(!$station->Company){
                return true;
            }
            $company=$station->Company;
            $response_company=$response_companies->where('id',$company->id)->first();
            $station->distance=round($station->distance,2);
            if($response_company){
                unset($station->Company);
                $response_company->stations->push($station);
                return true;
            }
            $company=$station->Company;
            unset($station->Company);
            $company->stations=collect();
            $company->stations->push($station);
            $response_companies->push($company);
            return true;
        });
        return $response_companies;
    }
    private function getHaversineFormula($latitude, $longitude): string
    {
        return "(6371 * acos(cos(radians($latitude))
                     * cos(radians(station.latitude))
                     * cos(radians(station.longitude)
                     - radians($longitude))
                     + sin(radians($latitude))
                     * sin(radians(station.latitude))))";
    }


}
