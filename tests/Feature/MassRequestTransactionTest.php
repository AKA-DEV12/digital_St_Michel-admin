<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MassRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Spatie\Permission\Models\Permission;

class MassRequestTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the permission and assign it to the user
        Permission::create(['name' => 'access_mass_requests']);
        
        $this->adminUser = User::factory()->create();
        $this->adminUser->givePermissionTo('access_mass_requests');
    }

    public function test_can_validate_mass_request_with_unique_transaction_id(): void
    {
        $massRequest = MassRequest::create([
            'requested_date' => now()->addDays(2),
            'time_slots' => ['09:00', '11:00'],
            'name1' => 'Jean Baptiste',
            'email' => 'jean.baptiste@example.com',
            'phone' => '0707070707',
            'request_object' => 'Messe d\'action de grâce',
            'amount' => 6000.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.mass_requests.validate', $massRequest->id), [
                'transaction_id' => 'TXN-ABC123XYZ',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $massRequest->refresh();
        $this->assertEquals('confirmed', $massRequest->status);
        $this->assertEquals('TXN-ABC123XYZ', $massRequest->transaction_id);
        $this->assertNotNull($massRequest->validated_at);
    }

    public function test_validation_fails_with_duplicate_transaction_id(): void
    {
        // Create an already confirmed mass request with a transaction ID
        MassRequest::create([
            'requested_date' => now()->addDays(2),
            'time_slots' => ['09:00'],
            'name1' => 'Pierre',
            'email' => 'pierre@example.com',
            'phone' => '0102030405',
            'request_object' => 'Messe de requiem',
            'amount' => 3000.00,
            'status' => 'confirmed',
            'transaction_id' => 'TXN-DUPLICATE',
            'validated_at' => now(),
        ]);

        // Create a pending request
        $pendingRequest = MassRequest::create([
            'requested_date' => now()->addDays(3),
            'time_slots' => ['11:00'],
            'name1' => 'Marie',
            'email' => 'marie@example.com',
            'phone' => '0505050505',
            'request_object' => 'Messe pour les malades',
            'amount' => 3000.00,
            'status' => 'pending',
        ]);

        // Try to validate the pending request with the same transaction ID
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.mass_requests.validate', $pendingRequest->id), [
                'transaction_id' => 'TXN-DUPLICATE',
            ]);

        $response->assertSessionHasErrors(['transaction_id']);
        $pendingRequest->refresh();
        $this->assertEquals('pending', $pendingRequest->status);
    }

    public function test_can_view_transactions_page(): void
    {
        // Create a validated mass request with a transaction ID
        MassRequest::create([
            'requested_date' => now()->addDays(2),
            'time_slots' => ['09:00'],
            'name1' => 'Pierre',
            'email' => 'pierre@example.com',
            'phone' => '0102030405',
            'request_object' => 'Messe',
            'amount' => 3000.00,
            'status' => 'confirmed',
            'transaction_id' => 'TXN-VALID-1',
            'validated_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.mass_requests.transactions'));

        $response->assertStatus(200);
        $response->assertSee('TXN-VALID-1');
        $response->assertSee('0102030405');
        $response->assertSee('pierre@example.com');
    }
}
