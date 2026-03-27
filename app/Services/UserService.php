<?php

namespace App\Services;

use App\Events\User\GenerateEmailVerification;
use App\Exceptions\Email\CodeAlreadyInvalidException;
use App\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Exceptions\Email\InvalidCodeException;
use App\Exceptions\Email\TooManyAttemptsException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public function token(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $tokenName = $request->input('user', 'insomnia');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @throws RandomException
     */
    public function registration($request)
    {
        $data = $request->validated();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);
        event(new GenerateEmailVerification($user->id));

        return $user;
    }

    /**
     * @throws Exception
     */
    public function verifyEmail(User $user, int $code): void
    {
        if ($user->isEmailVerified()) {
            throw new EmailAlreadyVerifiedException;
        }

        if ($user->email_verification_expires_at < now()) {
            throw new CodeAlreadyInvalidException;
        }

        if ($user->email_verification_code !== $code) {
            if ($user->email_verification_attempts > 5) {
                $user->update([
                    'email_verification_expires_at' => null,
                    'email_verification_code' => null,
                    'email_verification_attempts' => 0,
                ]);
                event(new GenerateEmailVerification($user->id));
                throw new TooManyAttemptsException;
            }

            $user->increment('email_verification_attempts');
            throw new InvalidCodeException;
        }



        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_attempts' => 0,
        ]);
    }
}
