<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TagController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $tags = Tag::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('slug', 'ILIKE', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($tags);
    }


    public function store(StoreTagRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag = Tag::create($data);

        return response()->json($tag, HttpResponse::HTTP_CREATED);
    }


    public function show(Tag $tag): JsonResponse
    {
        $tag->load('posts');

        return response()->json($tag);
    }


    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $data = $request->validated();

        if (array_key_exists('name', $data) && ! array_key_exists('slug', $data)) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag->update($data);

        return response()->json($tag);
    }


    public function destroy(Tag $tag): Response
    {
        $tag->delete();

        return response()->noContent(); 
    }
}
