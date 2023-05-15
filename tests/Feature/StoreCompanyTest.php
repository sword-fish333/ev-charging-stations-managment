<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Get an existing company from the database
        $company = Company::factory()->create();

        // Test data to use for the request
        $testData = [
            'name' => 'Test Company',
            'parent_company_id' => $company?->id
        ];

        // Send a post request to the endpoint
        $response = $this->postJson('/api/company', $testData);

        // Assert the company was created and the response has correct structure
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Company created successfully',
                'company' => [

                    'name' => $testData['name'],
                    'parent_company_id' => $testData['parent_company_id'] ?? null
                ]
            ]);

        // Assert the company was actually created
        $this->assertDatabaseHas('company', [

            'name' => $testData['name'],
            'parent_company_id' => $testData['parent_company_id'] ?? null
        ]);

        // Test validation rules
        $response = $this->postJson('/api/company', ['name' => '']);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson('/api/company', ['name' => str_repeat('a', 251)]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson('/api/company', ['parent_company_id' => 999]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('parent_company_id');
    }
}
