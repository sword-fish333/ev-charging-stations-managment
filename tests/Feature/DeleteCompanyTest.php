<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/company/{$company->id}");

        // Assert the company was deleted and the response has correctly structured
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Company deleted successfully'
            ]);

        $this->assertDatabaseMissing('company', ['id' => $company->id]);
    }
}
