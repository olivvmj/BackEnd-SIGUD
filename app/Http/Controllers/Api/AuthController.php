<?php

namespace App\Http\Controllers\Api;

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
                    "status" => false,
                    "pesan" => "Maaf, Akun Anda Tidak Ditemukan"
                ], 400);
            }

            $token = $data->createToken("auth_token")->plainTextToken;

            $response = [
                "status" => true,
                "user" => $data,
                "token" => $token
            ];

            return response($response, 201);

        });
    }

    public function logout(User $user){

        $user->tokens()->delete();

        return response()->json([
            "success" => true,
            "pesan" => "Logout berhasil"
        ], 200);
    }
}
