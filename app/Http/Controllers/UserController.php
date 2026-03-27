<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ChangeStatusRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\VerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
        $this->authorizeResource(User::class, 'user');
    }

    public function getBearerToken(Request $request)
    {
        return $this->userService->token($request);
    }

    public function index()
    {
        $users = User::query()->paginate(20);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->registration($request);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function changeStatus(ChangeStatusRequest $request, User $user)
    {
        $this->authorize('changeStatus', $user);
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $this->userService->verifyEmail($request->user(), $request->email_verification_code);

        return response()->json(['message' => 'Email verified'], 200);

    }
}
