<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_admin_can_create_product_with_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/products', [
            'name' => 'Дробь №5',
            'type' => 'shot',
            'description' => 'Охотничья дробь',
            'price_per_kg' => 350,
            'photo' => UploadedFile::fake()->image('shot.jpg'),
        ])->assertSessionHasNoErrors();

        $product = Product::firstOrFail();
        Storage::disk('public')->assertExists($product->photo_path);
        $this->get($product->photo_url)
            ->assertOk()
            ->assertHeader('content-type', 'image/jpeg');
    }

    public function test_landing_form_creates_lead_with_phone_or_email(): void
    {
        $this->post('/zayavki', [
            'name' => 'Иван',
            'phone' => '+7 999 123-45-67',
            'comment' => 'Нужна консультация',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas(Lead::class, ['name' => 'Иван']);
    }

    public function test_empty_contact_fields_return_only_one_error(): void
    {
        $this->post('/zayavki', [
            'name' => 'Иван',
            'phone' => '',
            'email' => '',
        ])->assertSessionHasErrors(['phone'])
            ->assertSessionDoesntHaveErrors(['email']);
    }

    public function test_user_can_register_and_login_with_login(): void
    {
        $this->post('/register', [
            'login' => 'admin_test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/dashboard');

        $this->post('/logout');

        $this->post('/login', [
            'login' => 'admin_test',
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_login_is_locked_for_an_hour_after_five_failures(): void
    {
        $user = User::factory()->create(['login' => 'locked_admin']);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', [
                'login' => $user->login,
                'password' => 'wrong-password',
            ]);
        }

        $this->post('/login', [
            'login' => $user->login,
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_later_users_must_be_approved_before_login(): void
    {
        $admin = User::factory()->create();

        $this->post('/register', [
            'login' => 'second_admin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/login');

        $candidate = User::where('login', 'second_admin')->firstOrFail();
        $this->assertNull($candidate->approved_at);
        $this->assertGuest();

        $this->post('/login', ['login' => 'second_admin', 'password' => 'password']);
        $this->assertGuest();

        $this->actingAs($admin)->patch("/admin/users/{$candidate->id}/approve");
        $this->post('/logout');
        $this->post('/login', ['login' => 'second_admin', 'password' => 'password']);
        $this->assertAuthenticatedAs($candidate);
    }

    public function test_admin_can_edit_password_and_delete_another_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['login' => 'old_login']);

        $this->actingAs($admin)->patch("/admin/users/{$user->id}", [
            'login' => 'new_login',
            'password' => 'new-password',
        ])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('new_login', $user->login);
        $this->assertTrue(Hash::check('new-password', $user->password));

        $this->actingAs($admin)->delete("/admin/users/{$user->id}");
        $this->assertModelMissing($user);
    }
}
