<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword']]);
    }

    public function resetPassword(Request $request)
    {
        // Validasi token, email, dan password baru
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // Cari reset token di database
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->input('email'))
            ->where('token', $request->input('token'))
            ->first();

        if (!$passwordReset || Carbon::now()->diffInMinutes($passwordReset->created_at) > 60) {
            return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa.'], 400);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            // Update password
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Hapus token reset setelah digunakan
            DB::table('password_resets')->where('email', $request->input('email'))->delete();

            return response()->json(['message' => 'Password telah direset.']);
        }

        return response()->json(['message' => 'User tidak ditemukan.'], 404);
    }

    public function forgotPassword(Request $request)
    {
        // Validasi email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // Buat token reset password
        $token = Str::random(60);
        $email = $request->input('email');

        // Simpan token ke database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Buat URL reset password
        $resetUrl = URL::temporarySignedRoute(
            'password.reset', // Route name
            Carbon::now()->addMinutes(60), // Expire time
            ['token' => $token, 'email' => $email] // Route parameters
        );

        // Kirim email reset password
        Mail::send('emails.reset_password', ['url' => $resetUrl], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Reset Password');
        });

        return response()->json(['message' => 'Email reset password telah dikirim.']);
    }

    public function register(Request $request)

    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        if($user){
            return response()->json(['message' => 'PENDAFTARAN BERHASIL']);
        }else{
            return response()->json(['message' => 'PENDAFTARAN GAGAL']);
        }

    }

    public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->messages(), 400);
    }

    $user = auth()->user();

    if ($request->has('name')) {
        $user->name = $request->input('name');
    }

    if ($user->save()) {
        return response()->json(['message' => 'USER BERHASIL DIPERBARUI']);
    } else {
        return response()->json(['message' => 'GAGAL MEMPERBARUI USER'], 500);
    }
}

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'BERHASIL LOGOUT']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}