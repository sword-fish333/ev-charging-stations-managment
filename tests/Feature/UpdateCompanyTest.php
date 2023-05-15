<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        // Create a company
        $company = Company::factory()->create();

        // New data to update the company
        $updateData = [
            'name' => 'Updated Company',
            'parent_company_id' => null
        ];

        // Send a post request to the endpoint
        $response = $this->postJson("/api/company/{$company->id}", $updateData);

        // Assert the company was updated and the response has correct structure
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Company updated successfully',
                'company' => [
                    'id' => $company->id,
                    'name' => $updateData['name'],
                    'parent_company_id' => $updateData['parent_company_id']
                ]
            ]);

        // Assert the company was actually updated
        $this->assertDatabaseHas('company', [
            'id' => $company->id,
            'name' => $updateData['name'],
            'parent_company_id' => $updateData['parent_company_id']
        ]);

        // Test validation rules
        $response = $this->postJson("/api/company/{$company->id}", ['name' => '']);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson("/api/company/{$company->id}", ['name' => str_repeat('a', 251)]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson("/api/company/{$company->id}", ['parent_company_id' => 999]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('parent_company_id');
    }
}
