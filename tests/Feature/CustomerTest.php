<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_belongs_to_category()
    {
        $category = CustomerCategory::create(['name' => 'TestCategory']);
        $customer = Customer::create([
            'name' => 'Test Customer',
            'reference' => 'REF001',
            'customer_category_id' => $category->id,
            'start_date' => now(),
            'description' => 'Test description',
        ]);

        $customer->load('category');

        $this->assertTrue($customer->category->is($category));
    }
}
