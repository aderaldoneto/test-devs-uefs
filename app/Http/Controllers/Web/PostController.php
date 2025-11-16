<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->with(['user', 'tags'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'ILIKE', "%{$search}%")
                      ->orWhere('content', 'ILIKE', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Posts/Index', [
            'posts'       => $posts,
            'filters'     => $request->only('search'),
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Posts/Create', [
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $post = Post::create([
            'title'   => $data['title'],
            'content' => $data['content'],
            'user_id' => auth()->id(), 
        ]);

        if (!empty($data['tag_ids'])) {
            $post->tags()->sync($data['tag_ids']);
        }

        return redirect()
            ->route('posts.index')
            ->with('status', 'Post criado com sucesso.');
    }

    public function show(Post $post): Response
    {
        $post->load(['user', 'tags']);

        return Inertia::render('Posts/Show', [
            'post'        => $post,
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function edit(Post $post): Response
    {
        $post->load(['user', 'tags']);

        return Inertia::render('Posts/Edit', [
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
            'post'        => $post,
            'tags'        => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'content'  => ['required', 'string'],
            'tag_ids'  => ['array'],
            'tag_ids.*'=> ['exists:tags,id'],
        ]);

        $post->update([
            'title'   => $data['title'],
            'content' => $data['content'],
        ]);

        $post->tags()->sync($data['tag_ids'] ?? []);

        return redirect()
            ->route('posts.index')
            ->with('status', 'Post atualizado com sucesso.');
    }
    
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('status', 'Post excluído com sucesso.');
    }
}
