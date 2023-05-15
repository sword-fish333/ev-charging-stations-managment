<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompaniesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // When
        // We hit the company index endpoint
        $response = $this->get('/api/company');

        // Then
        // We should receive a 200 OK
        $response->assertStatus(200);

        // Check the structure of the JSON response
        $response->assertJsonStructure([
            'success',
            'companies' => [
                '*' => [
                    'id',
                    'name',
                    'parent_company_id',
                    'stations' => [
                        '*' => [
                            'id',
                            'name',
                            'latitude',
                            'longitude',
                            'company_id',
                        ]
                    ],
                    'child_companies' => [
                        '*' => [
                            'id',
                            'name',
                            'parent_company_id',
                            'stations',
                            'child_companies',
                        ]
                    ]
                ]
            ]
        ]);


    }
}
