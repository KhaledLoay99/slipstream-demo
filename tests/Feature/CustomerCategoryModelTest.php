<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_customers()
    {
        $category = CustomerCategory::factory()->create();
        $customer = Customer::factory()->create(['customer_category_id' => $category->id]);

        $this->assertTrue($category->customers->contains($customer));
    }
}