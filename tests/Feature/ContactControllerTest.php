<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_contact()
    {
        $category = CustomerCategory::factory()->create();
        $customer = Customer::factory()->create(['customer_category_id' => $category->id]);

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'customer_id' => $customer->id,
        ];

        $response = $this->postJson('/contacts', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', $data);
    }

    /** @test */
    public function it_validates_create_request()
    {
        $response = $this->postJson('/contacts', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name', 'customer_id']);
    }

    /** @test */
    public function it_can_update_a_contact()
    {
        $category = CustomerCategory::factory()->create();
        $customer = Customer::factory()->create(['customer_category_id' => $category->id]);
        $contact = Contact::factory()->create(['customer_id' => $customer->id]);

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ];

        $response = $this->putJson("/contacts/{$contact->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'first_name' => 'Jane', 'last_name' => 'Smith']);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        $category = CustomerCategory::factory()->create();
        $customer = Customer::factory()->create(['customer_category_id' => $category->id]);
        $contact = Contact::factory()->create(['customer_id' => $customer->id]);

        $response = $this->deleteJson("/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
