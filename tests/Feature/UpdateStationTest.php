<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateStationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Arrange
        $company = Company::factory()->create();
        $station = Station::factory()->create([
            'name' => fake()->name,
            'address' => fake()->address,
            'company_id' => $company->id,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude
        ]);
        $updatedStationData = [
            'name' => fake()->name,
            'address' => fake()->address,
            'company_id' => $company->id,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude
        ];
        // Act
        $response = $this->postJson('api/station/'.$station->id, $updatedStationData);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Station updated successfully'
        ]);

        $this->assertDatabaseHas('station', $updatedStationData);
    }
}
