<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $attributes = $request->userData;

        if (User::firstWhere('email', $attributes['email']) !== null) {
            return response()->json('User with this email already exists', 403);
        }

        $user = User::create([
            'name'      => $attributes['fullName'],
            'email'     => $attributes['email'],
            'password'  => $attributes['password'],
            'address' => $attributes['address'],
            'city' => $attributes['city'],
            'country' => $attributes['country']
        ]);

        $token = sha1($user->email);

        $expires = Carbon::now()->addMinutes(30);

        Mail::to($user->email)->send(new VerificationMail($user, $expires, $token));

        return response()->json(201);
    }

    public function sendVerify(Request $request): JsonResponse
    {
        $attributes = $request->validate([
            'email' => 'required'
        ]);

        $user = User::firstWhere('email', $attributes['email']);

        $token = sha1($user->email);

        $expires = Carbon::now()->addMinutes(30);

        Mail::to($user->email)->send(new VerificationMail($user, $expires, $token));

        return response()->json(201);
    }

    public function verify(Request $request): JsonResponse
    {
        $attributes = $request->validate([
            'email' => 'required',
            'token' => 'required',
            'expires' => 'required',
        ]);
        $cur = new DateTime();
        $expires = new DateTime($attributes['expires']);

        if ($cur > $expires) {
            return response()->json(['error' => 'Expired.'], 401);
        }

        $user = User::where('email', $attributes['email']);

        if (sha1($attributes['email']) === $attributes['token']) {
            $user->update([
                'email_verified_at' => now(),
            ]);
        } else {
            return response()->json(['verify' => 'Something went wrong!']);
        }

        return response()->json(['message' => 'User was verified'], 200);
    }

    public function login(Request $request)
    {
        $attributes = $request->validate([
            'password' => 'required',
            'email' => 'required'
        ]);

        $user = User::firstWhere('email', $attributes['email']);

        if ($user->email_verified_at === null) {
            return response()->json('Email not verified', 401);
        }

        $credentials = [
            'email' => $attributes['email'],
            'password' => $attributes['password']
        ];

        if (Auth::attempt($credentials)) {
            return response()->json(200);
        }

        return response()->json(['message' => 'Invalid credentials'], 400);
    }

    public function user(): JsonResponse
    {
        $user = auth()->user();

        return response()->json($user);
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
