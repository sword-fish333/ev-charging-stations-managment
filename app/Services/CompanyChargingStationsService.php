<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Station;

class CompanyChargingStationsService
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function getChargingStationsForCoordinates($latitude,$longitude,$radius)
    {
        $company = $this->company;
        $company_ids = $company->childCompaniesIds();
        $company_ids->push($company->id);
        $haversine = $this->getHaversineFormula($latitude,$longitude);
        $stations = Station::whereIn('company_id', $company_ids->sort())->selectRaw("*,
                                            {$haversine} AS distance")->whereRaw("$haversine<=$radius")->with('Company')->orderBy('distance')->get();
        return $this->formatStations($stations);
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
