<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PostController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $posts = Post::query()
            ->with(['user', 'tags']) // carrega relações
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'ILIKE', "%{$search}%")
                      ->orWhere('content', 'ILIKE', "%{$search}%");
                });
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->integer('user_id'));
            })
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($posts);
    }


    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $post = Post::create($data);

        if (!empty($tags)) {
            $post->tags()->sync($tags);
        }

        $post->load(['user', 'tags']);

        return response()->json($post, HttpResponse::HTTP_CREATED);
    }


    public function show(Post $post): JsonResponse
    {
        $post->load(['user', 'tags']);

        return response()->json($post);
    }


    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();

        $tags = null;

        if (array_key_exists('tags', $data)) {
            $tags = $data['tags'] ?? [];
            unset($data['tags']);
        }

        $post->update($data);

        if ($tags !== null) {
            $post->tags()->sync($tags);
        }

        $post->load(['user', 'tags']);

        return response()->json($post);
    }


    public function destroy(Post $post): Response
    {
        $post->delete();

        return response()->noContent(); 
    }
}
