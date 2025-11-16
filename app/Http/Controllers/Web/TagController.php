<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Str;

class TagController extends Controller
{
    public function index(Request $request): Response
    {
        $tags = Tag::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('slug', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Tags/Index', [
            'tags'       => $tags,
            'filters'    => $request->only('search'),
            'canLogin'   => Route::has('login'),
            'canRegister'=> Route::has('register'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tags/Create', [
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Tag::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag criada com sucesso.');
    }

    public function edit(Tag $tag): Response
    {
        return Inertia::render('Tags/Edit', [
            'tag' => [
                'id'   => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ],
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tags', 'slug')->ignore($tag->id),
            ],
        ]);

        $tag->update($data);

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag atualizada com sucesso.');
    }


    public function show(Tag $tag, Request $request): Response
    {
        $posts = Post::query()
            ->with(['user', 'tags'])
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->orderByDesc('created_at')
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Tags/Show', [
            'tag'         => $tag,
            'posts'       => $posts,
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag excluída com sucesso.');
    }
}
