<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{UserCreateRequest, UserUpdateRequest};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $models = User::paginate();

        return UserResource::collection($models);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->validated();

        if (User::whereEmail($data['email'])->exists()) {
            return response()->json(['error' => 'user_already_exists'], 400);
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if ($data['name']) {
            $user->name = $data['name'];
        }

        if ($data['email']) {
            if (User::whereEmail($data['email'])->exists()) {
                return response()->json(['error' => 'email_already_in_use'], 400);
            }

            $user->email = $data['email'];
        }

        if ($data['password']) {
            $user->password = Hash::make($data['password']);
        }

        $user->saveOrFail();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return response(null, 204);
    }
}
