<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(LoginRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $data = $this->user->where("username", $request->username)->first();

            if (!$data || !Hash::check($request->password, $data->password)) {
                return response()->json([
                    'kode' => 400,
                    'status' => false,
                    'message' => "Maaf, Akun Anda Tidak Ditemukan"
                ]);
            }
            // $user = Auth::user();
            // $user->hasRole('superAdmin');
            $token = $data->createToken("auth_token")->plainTextToken;

            $response = [
                'kode' => 200,
                'status' => true,
                'user' => $data,
                'token' => $token
            ];

            return response($response);

        });
    }

    public function logout(User $user){

        $user->tokens()->delete();

        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => "Logout berhasil"
        ]);
    }
}
