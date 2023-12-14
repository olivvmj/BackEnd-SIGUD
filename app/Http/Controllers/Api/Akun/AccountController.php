<?php

namespace App\Http\Controllers\API\Akun;

use App\Models\Role;
use App\Models\User;
use App\Models\DataUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function createOperator (Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,username',
                'name' => 'required',
                'no_hp' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // contoh aturan validasi untuk gambar
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'kode' => 400,
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ]);
            }

            // Use database transaction
            DB::beginTransaction();

            try {
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

                $filename = "";
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

                // Commit the transaction if all operations were successful
                DB::commit();

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => 'Berhasil Menambahkan Data Operator!',
                    'data' => $dataUser
                ]);
            } catch (\Exception $e) {
                // Rollback the transaction if an exception occurs
                DB::rollBack();

                return response()->json([
                    'kode' => 500,
                    'status' => false,
                    'message' => 'Terjadi Kesalahan Saat Menyimpan Data',
                    'error' => $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., validation exceptions) here
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createClient (Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,username',
                'name' => 'required',
                'no_hp' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // contoh aturan validasi untuk gambar
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'kode' => 400,
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ]);
            }

            // Use database transaction
            DB::beginTransaction();

            try {
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
                $user->assignRole('client');

                $filename = "";
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

                // Commit the transaction if all operations were successful
                DB::commit();

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => 'Berhasil Menambahkan Data Client!',
                    'data' => $dataUser
                ]);
            } catch (\Exception $e) {
                // Rollback the transaction if an exception occurs
                DB::rollBack();

                return response()->json([
                    'kode' => 500,
                    'status' => false,
                    'message' => 'Terjadi Kesalahan Saat Menyimpan Data',
                    'error' => $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., validation exceptions) here
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ]);
        }
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
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'kode' => 404,
                    'status' => false,
                    'message' => 'user tidak terdaftar'
                ]);
            }

            DataUser::where('user_id', $id)->delete();
            $user->delete();
            DB::commit();

            return response()->json([
                'kode' => 200,
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
    }

    public function getOperator()
    {
        $operatorData = DataUser::join('users', 'users.id', '=', 'data_users.user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'operator')
            ->select('users.username', 'users.name', 'data_users.image', 'data_users.no_hp')
            ->get();

        if (!$operatorData) {
            return response()->json([
                'kode' => 204,
                'status' => false,
                'message' => 'Data Operator Kosong',
                'error' => 'Tidak ada operator yang tersedia',
            ]);
        }

        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'Daftar Data Operator',
            'data' => $operatorData
        ]);
    }

    public function getClient()
    {
        $clientData = DataUser::join('users', 'users.id', '=', 'data_users.user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'client')
            ->select('users.username', 'users.name', 'data_users.image', 'data_users.no_hp')
            ->get();

        if (!$clientData) {
            return response()->json([
                'kode' => 204,
                'status' => false,
                'message' => 'Data Client Kosong',
                'error' => 'Tidak ada Client yang tersedia',
            ]);
        }

        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'Daftar Data Client',
            'data' => $clientData
        ]);
    }
}
