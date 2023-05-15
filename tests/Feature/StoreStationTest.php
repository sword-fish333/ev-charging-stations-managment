<?php

namespace Tests\Feature;

use App\Models\Company;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreStationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Arrange
        $company = Company::factory()->create();
        $stationData = [
            'name' => fake()->name,
            'address' => fake()->address,
            'company_id' => $company->id,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude
        ];

        // Act
        $response = $this->postJson('api/station', $stationData);

        // Assert
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'success' => true,
            'message' => 'Station created successfully'
        ]);

        $this->assertDatabaseHas('station', $stationData);
    }
}
