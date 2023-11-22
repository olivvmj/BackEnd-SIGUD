<?php

namespace App\Http\Controllers\API\Akun;

use App\Models\User;
use App\Models\DataUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $user;
    protected $dataUser;

    public function __construct(User $user, DataUser $dataUser)
    {
        $this->user = $user;

        $this->dataUser = DataUser::join('users', 'users.id', '=', 'data_users.user_id')
            ->select('users.username', 'users.name', 'data_users.image', 'data_users.no_hp')
            ->get();

        $this->middleware('auth');
    }

    public function index()
    {
        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'Menampilkan Daftar Pengguna',
            'data' => $this->dataUser
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    public function createOperator(Request $request)
    {
        // if ($request->user() && $request->user()->hasRole('superAdmin')) {
            // Menyiapkan nilai default untuk pengguna
            $default_user_value = [
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ];

            // Membuat pengguna baru
            $user = User::create(array_merge([
                'username' => $request->username,
                'name'  => $request->name,
            ], $default_user_value));

            // Memberi role pada user baru
            $user->assignRole('operator');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Carbon::now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();
                $path = 'profile/' . $filename;
                Storage::disk('local')->put($path, file_get_contents($image));
            }

            // Membuat data pengguna baru
            $dataUser = DataUser::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'image' => $filename,
                'no_hp' => $request->no_hp,
            ]);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Success Add Data Operator!',
                'data' => $dataUser
            ]);
        // } else {
        //     return response()->json([
        //         'status' => 403,
        //         'message' => 'Permission denied.',
        //     ]);
        // }
    }

    public function createClient(Request $request)
    {
            // Menyiapkan nilai default untuk pengguna
            $default_user_value = [
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ];

            // Membuat pengguna baru
            $user = User::create(array_merge([
                'username' => $request->username,
                'name'  => $request->name,
            ], $default_user_value));

            // Memberi role pada user baru
            $user->assignRole('operator');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Carbon::now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();
                $path = 'profile/' . $filename;
                Storage::disk('local')->put($path, file_get_contents($image));
            }

            // Membuat data pengguna baru
            $dataUser = DataUser::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'image' => $filename,
                'no_hp' => $request->no_hp,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Success Add Data Operator!',
                'data' => $dataUser
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $userRoles = $request->user()->getRoleNames();

        if ($request->user() && $request->user()->hasRole('superAdmin')) {
            DB::beginTransaction();
            try {
                $user = User::find($id);
                if (!$user) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 404,
                        'message' => 'user tidak terdaftar'
                    ]);
                }

                DataUser::where('user_id', $id)->delete();
                $user->delete();
                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil menghapus data user'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => 500,
                    'message' => 'Terjadi kesalahan saat menghapus data user',
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            $response = [
                'status' => 403,
                'message' => 'Permission denied',
                'userRoles' => $userRoles,
            ];

            return response()->json($response);
        }
    }
}
