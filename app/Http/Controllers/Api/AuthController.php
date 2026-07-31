<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    private const REFRESH_COOKIE_NAME = 'refresh_token';
    private const ACCESS_TOKEN_TTL_MINUTES = 15;
    private const REFRESH_TOKEN_TTL_DAYS = 30;

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return $this->respondWithTokens($user, $request->boolean('remember_me'), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        return $this->respondWithTokens($user, $request->boolean('remember_me'));
    }

    /**
     * Tukar refresh token (dari httpOnly cookie) jadi access token baru.
     * Refresh token yang lama SELALU di-revoke dan diganti baru (rotasi),
     * terlepas berhasil atau gagal dipakai — mencegah refresh token yang
     * sama dipakai berulang kali kalau ada yang berhasil mencurinya.
     */
    public function refresh(Request $request): JsonResponse
    {
        $plainToken = $request->cookie(self::REFRESH_COOKIE_NAME);

        if (! $plainToken) {
            return response()->json(['message' => 'Sesi tidak ditemukan.'], 401)
                ->withCookie($this->forgetRefreshCookie());
        }

        $tokenHash = hash('sha256', $plainToken);
        $stored = RefreshToken::where('token_hash', $tokenHash)->first();

        if (! $stored || ! $stored->isValid()) {
            $stored?->update(['revoked_at' => now()]);

            return response()->json(['message' => 'Sesi sudah tidak berlaku, silakan login lagi.'], 401)
                ->withCookie($this->forgetRefreshCookie());
        }

        $stored->update(['revoked_at' => now()]);

        return $this->respondWithTokens($stored->user, $stored->remember);
    }

    public function logout(Request $request): JsonResponse
    {
        $plainToken = $request->cookie(self::REFRESH_COOKIE_NAME);

        if ($plainToken) {
            RefreshToken::where('token_hash', hash('sha256', $plainToken))
                ->update(['revoked_at' => now()]);
        }

        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Berhasil logout.'])
            ->withCookie($this->forgetRefreshCookie());
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        PasswordFacade::sendResetLink($request->only('email'));

        return response()->json([
            'message' => 'Jika email terdaftar, tautan reset password sudah dikirim.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $status = PasswordFacade::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Cabut semua access token DAN refresh token milik user ini
                // supaya sesi di semua device otomatis logout setelah
                // password direset.
                $user->tokens()->delete();
                RefreshToken::where('user_id', $user->id)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);
            }
        );

        if ($status !== PasswordFacade::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json([
            'message' => 'Password berhasil direset. Silakan login dengan password baru.',
        ]);
    }

    /**
     * Buat access token (Sanctum, umur pendek) + refresh token (cookie
     * httpOnly, umur panjang) sekaligus, lalu kembalikan sebagai response.
     */
    private function respondWithTokens(User $user, bool $remember, int $status = 200): JsonResponse
    {
        $accessToken = $user->createToken(
            'pathskill-web-access',
            ['*'],
            now()->addMinutes(self::ACCESS_TOKEN_TTL_MINUTES)
        )->plainTextToken;

        $plainRefreshToken = Str::random(64);

        RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainRefreshToken),
            'remember' => $remember,
            'expires_at' => now()->addDays(self::REFRESH_TOKEN_TTL_DAYS),
        ]);

        return response()->json([
            'token' => $accessToken,
            'user' => $user,
        ], $status)->withCookie(
            $this->makeRefreshCookie($plainRefreshToken, $remember)
        );
    }

    private function makeRefreshCookie(string $value, bool $remember): Cookie
    {
        // minutes = 0 -> Laravel akan membuat SESSION cookie (tidak ada
        // Expires/Max-Age, browser hapus otomatis saat ditutup). Ini yang
        // bikin "Ingat saya" tidak dicentang benar-benar berarti "jangan
        // ingat setelah tab ditutup", bukan cuma di sisi frontend.
        $minutes = $remember ? self::REFRESH_TOKEN_TTL_DAYS * 24 * 60 : 0;

        return cookie(
            name: self::REFRESH_COOKIE_NAME,
            value: $value,
            minutes: $minutes,
            path: '/',
            domain: config('session.domain'),
            secure: config('session.secure_cookie', false),
            httpOnly: true,
            sameSite: config('session.same_site', 'lax'),
        );
    }

    private function forgetRefreshCookie(): Cookie
    {
        return cookie()->forget(self::REFRESH_COOKIE_NAME);
    }
}