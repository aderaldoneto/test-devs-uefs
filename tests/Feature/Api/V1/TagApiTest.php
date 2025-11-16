<?php

namespace Tests\Feature\Api\V1;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }


    public function test_it_lists_tags_with_pagination_and_search(): void
    {
        Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        Tag::factory()->create(['name' => 'Vue.js', 'slug' => 'vue-js']);
        Tag::factory(8)->create();

        $response = $this->getJson('/api/v1/tags?per_page=5&search=laravel');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                    ]
                ],
                'meta',
                'links',
            ])
            ->assertJsonFragment(['name' => 'Laravel']);
    }

    public function test_it_creates_a_tag_and_generates_slug_if_empty(): void
    {
        $payload = [
            'name' => 'Desenvolvimento Web',
            'slug' => null,
        ];

        $response = $this->postJson('/api/v1/tags', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Desenvolvimento Web')
            ->assertJsonPath('data.slug', 'desenvolvimento-web');

        $this->assertDatabaseHas('tags', [
            'name' => 'Desenvolvimento Web',
            'slug' => 'desenvolvimento-web',
        ]);
    }

    public function test_it_validates_unique_name_when_creating_a_tag(): void
    {
        Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

        $payload = [
            'name' => 'Laravel',
        ];

        $response = $this->postJson('/api/v1/tags', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_shows_a_single_tag(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Backend',
            'slug' => 'backend',
        ]);

        $response = $this->getJson("/api/v1/tags/{$tag->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $tag->id)
            ->assertJsonPath('data.name', 'Backend');
    }

    public function test_it_updates_a_tag_and_generates_slug_if_not_provided(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Api Rest',
        ]);

        $payload = [
            'name' => 'API RESTful',
        ];

        $response = $this->putJson("/api/v1/tags/{$tag->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('data.name', 'API RESTful');

        $this->assertDatabaseHas('tags', [
            'id'   => $tag->id,
            'name' => 'API RESTful',
        ]);
    }

    public function test_it_soft_deletes_a_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->deleteJson("/api/v1/tags/{$tag->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('tags', [
            'id' => $tag->id,
        ]);
    }
}
