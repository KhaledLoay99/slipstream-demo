<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_customer()
    {
        $customer = Customer::factory()->create();
        $contact = Contact::factory()->create(['customer_id' => $customer->id]);

        $this->assertEquals($customer->id, $contact->customer->id);
    }
}