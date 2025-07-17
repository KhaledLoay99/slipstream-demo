<?php

namespace Tests\Feature;

use App\Models\CustomerCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer()
    {
        $category = CustomerCategory::create(['name' => 'Gold']);

        $response = $this->post('/customers', [
            'name' => 'Test Customer',
            'reference' => 'REF001',
            'customer_category_id' => $category->id,
            'start_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', ['name' => 'Test Customer']);
    }
}
