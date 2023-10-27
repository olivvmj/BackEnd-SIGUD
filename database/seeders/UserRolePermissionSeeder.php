<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    public function run()
    {
        $default_user_value = [
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        DB::beginTransaction();

        try {
            $superAdmin = User::create(array_merge([
                'role_id' => '1',
                'name' => 'Super Admin',
                'username' => 'SuperAdmin',
            ], $default_user_value));

            $operator = User::create(array_merge([
                'role_id' => '2',
                'name' => 'Operator',
                'username' => 'operator1',
            ], $default_user_value));

            $client = User::create(array_merge([
                'role_id' => '3',
                'name' => 'Peminjam',
                'username' => 'Peminjam',
            ], $default_user_value));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
