<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Station;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChargingStationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // test data
        $test_radius = 100;
        $test_lat = 55.860916;
        $test_lng = -4.251433;

        $company_name = fake()->name();
        $station1_name = fake()->name();
        $station2_name = fake()->name();
        $latitude1 = '55.800916';
        $longitude1 = '-4.201400';
        $latitude2 = '55.810916';
        $longitude2 = '-4.151400';
        $company = Company::factory()->create(['name' => $company_name]);
        $station1 = Station::factory()->create([

            'name' => $station1_name,
            'latitude' => $latitude1,
            'longitude' => $longitude1,
            'company_id' => $company->id
        ]);
        $station2 = Station::factory()->create([

            'name' => $station2_name,
            'latitude' => $latitude2,
            'longitude' => $longitude2,
            'company_id' => $company->id
        ]);
        $distance1 = round(haversineGreatCircleDistance($test_lat, $test_lng, $latitude1, $longitude1) / 1000, 2);
        $distance2 = round(haversineGreatCircleDistance($test_lat, $test_lng, $latitude2, $longitude2) / 1000, 2);

        // Act
        $company_id = $company->id;
        $response = $this->getJson("/api/company/{$company_id}/charging-stations?latitude={$test_lat}&longitude={$test_lng}&radius={$test_radius}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Available stations',
                'data' => [
                    [
                        'id' => $company->id,
                        'name' => $company->name,
                        'parent_company_id' => $company->parent_company_id,
                        'stations' => [
                            [
                                'id' => $station1->id,
                                'name' => $station1_name,
                                'latitude' => $latitude1,
                                'longitude' => $longitude1,
                                'company_id' => $company->id,
                                "distance" => $distance1

                            ],
                            [
                                'id' => $station2->id,
                                'name' => $station2_name,
                                'latitude' => $latitude2,
                                'longitude' => $longitude2,
                                'company_id' => $company->id,
                                "distance" => $distance2

                            ]
                        ],
                    ]
                ]
            ]);
    }
}
