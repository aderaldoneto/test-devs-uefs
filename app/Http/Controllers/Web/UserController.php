<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users'       => $users,
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create', [
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário criado com sucesso.');
    }


    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user'        => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]); //pra não precisa da senha pra editar

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        if ($user->id != auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('status', 'Usuário excluído com sucesso.');
        } else {
            return redirect()
                ->route('login');
        }

    }
}
