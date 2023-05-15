<?php

namespace App\Models;

use App\Services\CompanyChargingStationsService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];

    public function subCompanies()
    {
        return $this->hasMany(self::class, 'parent_company_id');
    }

    public function childCompanies()
    {
        return $this->subCompanies()->with('Stations')->with('childCompanies');
    }

    public function Stations()
    {
        return $this->hasMany(Station::class, 'company_id');
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_company_id');
    }

    public function childCompaniesIds()
    {
        $child_companies = $this->childCompanies;
        if (!$child_companies) {
            return [];
        }
        $ids = $child_companies->pluck('id')->flatten();
        $child_companies->each(function ($company) use (&$ids) {
            $ids = $ids->merge($company->childCompaniesIds());
        });
        return $ids;
    }

    public function chargingStationsForCoordinates($latitude, $longitude, $radius): \Illuminate\Support\Collection
    {
        return (new CompanyChargingStationsService($this))->getChargingStationsForCoordinates($latitude, $longitude, $radius);
    }
}
