<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AclController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::query()->create($data);
        return successResponse();
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->findUser($data['username']);
        $this->checkPassword($user, $data['password']);
        $token = $user->newToken();
        return successResponse([
            'user' => $user->getAttribute('username'),
            'token' => $token
        ]);
    }

    /**
     * @param $username
     * @return Model|User
     */
    protected function findUser($username): Model|User
    {
        return User::query()->where('username', $username)->firstOrFail();
    }

    /**
     * @param $user
     * @param $password
     * @return void
     * @throws CustomException
     */
    protected function checkPassword($user, $password): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new CustomException('No credentials found with provided data');
        }

    }
}
