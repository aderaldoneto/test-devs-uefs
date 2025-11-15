<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user); // <--- usuário autenticado para todos os testes deste arquivo
    }


    public function test_it_lists_users_with_pagination_and_search(): void
    {
        User::factory()->create([
            'name'  => 'Michael Jackson',
            'email' => 'mj@kingofpop.com',
        ]);
        
        User::factory(8)->create();
        $response = $this->getJson('/api/v1/users?per_page=5&search=michael');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'meta',
                'links',
            ])
            ->assertJsonFragment(['name' => 'Michael Jackson']);
    }

    public function test_it_creates_a_user_and_hashes_password(): void
    {
        $payload = [
            'name'     => 'Aderaldo Neto',
            'email'    => 'aderaldo@neto.com',
            'password' => '123456789',
        ];

        $response = $this->postJson('/api/v1/users', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Aderaldo Neto')
            ->assertJsonPath('data.email', 'aderaldo@neto.com');

        $this->assertDatabaseHas('users', [
            'name'  => 'Aderaldo Neto',
            'email' => 'aderaldo@neto.com',
        ]);

        $user = User::where('email', 'aderaldo@neto.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('123456789', $user->password));
    }

    public function test_it_validates_required_fields_when_creating_a_user(): void
    {
        $response = $this->postJson('/api/v1/users', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_it_shows_a_single_user(): void
    {
        $user = User::factory()->create([
            'name'  => 'Prince',
            'email' => 'prince@king.com',
        ]);
        $response = $this->getJson("/api/v1/users/{$user->id}");
        $response->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', 'Prince')
            ->assertJsonPath('data.email', 'prince@king.com');
    }

    public function test_it_updates_a_user_and_hashes_password_if_provided(): void
    {
        $user = User::factory()->create([
            'name'  => 'Ivete Sangaloo',
            'email' => 'cantoraivete@ivete.com',
            'password' => Hash::make('oldpass'),
        ]);

        $payload = [
            'name'     => 'Ivete Sangalo',
            'email'    => 'ivete@ivete.com',
            'password' => '123456789',
        ];

        $response = $this->putJson("/api/v1/users/{$user->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Ivete Sangalo')
            ->assertJsonPath('data.email', 'ivete@ivete.com');

        $user->refresh();

        $this->assertEquals('Ivete Sangalo', $user->name);
        $this->assertEquals('ivete@ivete.com', $user->email);
        $this->assertTrue(Hash::check('123456789', $user->password));
    }

    public function test_it_soft_deletes_a_user(): void
    {
        $user = User::factory()->create();
        $response = $this->deleteJson("/api/v1/users/{$user->id}");
        $response->assertNoContent();
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }
}
