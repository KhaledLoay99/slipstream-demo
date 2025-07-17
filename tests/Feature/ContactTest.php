<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_belongs_to_customer()
    {
        $category = CustomerCategory::create(['name' => 'Test Category']);

        $customer = Customer::create([
            'name' => 'Test Customer',
            'reference' => 'REF001',
            'customer_category_id' => $category->id,
            'start_date' => now(),
            'description' => 'Test description',
        ]);

        $contact = Contact::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'customer_id' => $customer->id,
        ]);

        $contact->load('customer');

        $this->assertTrue($contact->customer->is($customer));
    }
}