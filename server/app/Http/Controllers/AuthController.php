<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->userFor($request);

        $tokens = $user->tokens()->paginate();

        return PersonalAccessTokenResource::collection($tokens);
    }

    public function authenticate(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::whereEmail($data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        if (Hash::needsRehash($user->password)) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $token = $user->createToken($data['client_name']);

        return ['token' => $token->plainTextToken];
    }

    public function revoke(Request $request, string $id)
    {
        $user = $this->userFor($request);

        $user->tokens()->where('id', '=', $id)->delete();

        return response(null, 204);
    }

    public function revokeAll(Request $request)
    {
        $user = $this->userFor($request);

        $user->tokens()->delete();

        return response(null, 204);
    }

    private function userFor(Request $request): User
    {
        $user = $request->user();
        if (!$user || !$user instanceof User) {
            abort(401);
        }

        return $user;
    }
}
