<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints de posts"
 * )
 */
class PostController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     tags={"Posts"},
     *     summary="Lista posts",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filtro por title ou content",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filtro posts por usuário",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada dos posts"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $posts = Post::query()
            ->with(['user', 'tags']) 
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

        return response()->json(
        PostResource::collection($posts)->response()->getData(true)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     tags={"Posts"},
     *     summary="Cria um novo post",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "title", "content"},
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="published_at", type="string", format="date"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer")),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de criação"
     *     ),
     * )
     */
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

        return (new PostResource($post))
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/posts/{id}",
     *     tags={"Posts"},
     *     summary="Detalha um post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não encontrada"
     *     )
     * )
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['user', 'tags']);
        return (new PostResource($post))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{id}",
     *     tags={"Posts"},
     *     summary="Edita um post",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="title", type="string", example="Meu titulo"),
     *             @OA\Property(property="content", type="string", example="Meu conteudo"),
     *             @OA\Property(property="published_at", type="string", format="date"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer")),
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post atualizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não atualizado"
     *     )
     * )
     */
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
        return (new PostResource($post))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{id}",
     *     tags={"Posts"},
     *     summary="Deleta um post",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deletado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não deletada"
     *     )
     * )
     */
    public function destroy(Post $post): Response
    {
        $post->delete();
        return response()->noContent(); 
    }
}
