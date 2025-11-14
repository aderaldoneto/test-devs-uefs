<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($users);
    }

    
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user, HttpResponse::HTTP_CREATED); // 201
    }

    
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    
    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent(); // 204
    }
}
