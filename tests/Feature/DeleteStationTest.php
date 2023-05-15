<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteStationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $company = Company::factory()->create();

        $station = Station::factory()->create([
            'name' => fake()->name,
            'address' => fake()->address,
            'company_id' => $company->id,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude
        ]);

        $response = $this->deleteJson("/api/station/{$station->id}");

        // Assert the company was deleted and the response has correctly structured
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Station deleted successfully'
            ]);

        $this->assertDatabaseMissing('station', ['id' => $station->id]);
    }
}
