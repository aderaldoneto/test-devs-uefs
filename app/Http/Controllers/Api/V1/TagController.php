<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\V1\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Endpoints de tags"
 * )
 */
class TagController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     tags={"Tags"},
     *     summary="Lista tags",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filtro por nome ou slug",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *         description="Lista paginada de tags"
     *     )
     * )
     */
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

        return response()->json( 
            TagResource::collection($tags)->response()->getData(true)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tags",
     *     tags={"Tags"},
     *     summary="Cria uma nova tag",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Tecnologia da Informação"),
     *             @OA\Property(property="slug", type="string", example="tecnologia-da-informacao"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag criada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     ),
     * )
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        // pra garantir unicidade
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (Tag::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $data['slug'] = $slug;

        $tag = Tag::create($data);

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     *     tags={"Tags"},
     *     summary="Detalha uma tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não encontrada"
     *     )
     * )
     */
    public function show(Tag $tag): JsonResponse
    {
        $tag->load('posts');
        return (new TagResource($tag))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     *     tags={"Tags"},
     *     summary="Edita uma tag",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Tecnologia"),
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
     *         description="Tag atualizada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não atualizada"
     *     )
     * )
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {   
        // só pode atualizar o nome, o slug mantem
        $data = $request->validated();
        $tag->update($data);
        return (new TagResource($tag))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tags/{id}",
     *     tags={"Tags"},
     *     summary="Deleta uma tag",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag deletada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não deletada"
     *     )
     * )
     */
    public function destroy(Tag $tag): Response
    {
        $tag->delete();
        return response()->noContent(); 
    }
}
