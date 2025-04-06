<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	protected UserService $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}
	public function register(RegisterRequest $request)
	{
		$user = $this->userService->store($request->validated());

		$token = $user->createToken($request->role. '_auth_token')->plainTextToken;

		// Send verification email
		$user->sendEmailVerificationNotification();

		return response()->json([
			'message' => 'User registered. Please check your email to verify your account.',
			'token' => $token,
		], Response::HTTP_CREATED);

	}
    public function login(LoginRequest $request)
    {
		$user = $this->userService->findByEmail($request->email);

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}

		$token = $user->createToken($user->role. '_auth_token')->plainTextToken;

		return response()->json(['token' => $token, 'user' => $user], Response::HTTP_OK);
    }
	public function logout(LogoutRequest $request)
	{
		$request->user()->tokens()->delete();
		return response()->json(['message' => 'Logged out'], Response::HTTP_OK);
	}
}
