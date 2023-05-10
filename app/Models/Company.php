<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];

    public function childCompanies()
    {
        return $this->hasMany(self::class, 'parent_company_id');
    }

    public function Stations()
    {
        return $this->hasMany(Station::class, 'company_id');
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_company_id');
    }

    public function childCompaniesIds(){
       return $this->childCompanies->pluck('id')->flatten();
    }
}
