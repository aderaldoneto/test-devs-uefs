<?php

namespace Tests\Feature\Api\V1;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }


    public function test_it_lists_posts_with_pagination_and_relations(): void
    {
        $user = User::factory()->create();
        $tags = Tag::factory(3)->create();

        Post::factory(5)
            ->for($user)
            ->create()
            ->each(function ($post) use ($tags) {
                $post->tags()->attach($tags->random(2));
            });

        $response = $this->getJson('/api/v1/posts?per_page=3');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'user',
                        'tags',
                    ]
                ],
                'meta',
                'links'
            ]);
    }

    public function test_it_filters_posts_by_user_id(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Post::factory(3)->for($userA)->create();
        Post::factory(2)->for($userB)->create();

        $response = $this->getJson('/api/v1/posts?user_id=' . $userA->id);

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 3);
    }

    public function test_it_creates_a_post_with_tags(): void
    {
        $user = User::factory()->create();
        $tags = Tag::factory(3)->create();

        $payload = [
            'user_id'      => $user->id,
            'title'        => 'Meu Post',
            'content'      => 'Conteúdo de teste',
            'published_at' => now()->toIso8601String(),
            'tags'         => $tags->take(2)->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/posts', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Meu Post')
            ->assertJsonCount(2, 'data.tags');

        $postId = $response->json('data.id');

        $this->assertDatabaseHas('posts', [
            'id'      => $postId,
            'user_id' => $user->id,
        ]);

        foreach ($payload['tags'] as $tagId) {
            $this->assertDatabaseHas('post_tag', [
                'post_id' => $postId,
                'tag_id'  => $tagId,
            ]);
        }
    }

    public function test_it_validates_required_fields_when_creating_a_post(): void
    {
        $response = $this->postJson('/api/v1/posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'title', 'content']);
    }

    public function test_it_shows_a_single_post_with_relations(): void
    {
        $user = User::factory()->create();
        $tags = Tag::factory(2)->create();

        $post = Post::factory()->for($user)->create();
        $post->tags()->attach($tags->pluck('id')->toArray());

        $response = $this->getJson("/api/v1/posts/{$post->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $post->id)
            ->assertJsonCount(2, 'data.tags');
    }

    public function test_it_updates_a_post_and_syncs_tags(): void
    {
        $user = User::factory()->create();
        $tags = Tag::factory(4)->create();

        $post = Post::factory()->for($user)->create();
        $post->tags()->attach($tags->take(2)->pluck('id'));

        $payload = [
            'title' => 'Atualizado',
            'tags'  => $tags->slice(2, 2)->pluck('id')->toArray(),
        ];

        $response = $this->putJson("/api/v1/posts/{$post->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Atualizado')
            ->assertJsonCount(2, 'data.tags');

        foreach ($tags->take(2) as $tag) {
            $this->assertDatabaseMissing('post_tag', [
                'post_id' => $post->id,
                'tag_id'  => $tag->id,
            ]);
        }

        foreach ($payload['tags'] as $tagId) {
            $this->assertDatabaseHas('post_tag', [
                'post_id' => $post->id,
                'tag_id'  => $tagId,
            ]);
        }
    }

    public function test_it_soft_deletes_a_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/v1/posts/{$post->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }
}
