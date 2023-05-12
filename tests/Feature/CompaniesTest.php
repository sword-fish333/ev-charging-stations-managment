<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompaniesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Given
        // Create some companies
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        // When
        // We hit the company index endpoint
        $response = $this->get('/api/company');

        // Then
        // We should receive a 200 OK
        $response->assertStatus(200);

        // And also we should receive the companies we created
        $response->assertJson([
            'success' => true,
            'companies' => [
                [
                    'id' => $company1->id,
                    'name' => $company1->name,
                ],
                [
                    'id' => $company2->id,
                    'name' => $company2->name,

                ],
            ]
        ]);

    }
}
