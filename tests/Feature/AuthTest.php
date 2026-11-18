<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sign_in_and_open_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@toko.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->get(route('login'))->assertOk()->assertSee('Masuk ke');

        $this->post(route('login.store'), [
            'email' => 'admin@toko.test',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Ringkasan toko')
            ->assertSee('Kelola produk');
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'admin@toko.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'admin@toko.test',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_cannot_open_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_user_can_sign_out(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
