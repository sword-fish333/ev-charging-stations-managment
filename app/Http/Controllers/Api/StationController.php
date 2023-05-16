<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCompanyRequest;
use App\Http\Requests\Api\StoreStationRequest;
use App\Models\Company;
use App\Models\Station;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::orderByDesc('id')->get();
        return \response()->json(['success' => true, 'stations' => $stations]);
    }

    public function childCompanies($company_id)
    {
        $companies = Company::where('parent_company_id', $company_id)->orderByDesc('id')->get();
        return \response()->json(['success' => true, 'child_companies' => $companies]);
    }

    public function store(StoreStationRequest $request)
    {
        $validated = $request->validated();
        try {
            $station = Station::create($validated);
            return response()->json(['success' => true, 'message' => 'Station created successfully', 'station' => $station], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function update(StoreStationRequest $request, Station $station)
    {
        $validated = $request->validated();
        try {
            $station->update($validated);
            return response()->json(['success' => true, 'message' => 'Station updated successfully', 'station' => $station], Response::HTTP_OK);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function delete(Station $station)
    {
        try {
            $station->delete();
            return response()->json(['success' => true, 'message' => 'Station deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            fullLog($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

}
