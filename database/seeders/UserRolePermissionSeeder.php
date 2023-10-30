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
                'username' => 'superAdmin',
                'name'  => 'Super Admin',
            ], $default_user_value));

            $operator = User::create(array_merge([
                'username' => 'NamaOperator',
                'name'  => 'Operator',
            ], $default_user_value));

            $client = User::create(array_merge([
                'username' => 'NamaClient',
                'name'  => 'Client',
            ], $default_user_value));

            $role_superAdmin = Role::create(['name' =>'superAdmin']);
            $role_operator = Role::create(['name' => 'operator']);
            $role_client = Role::create(['name' => 'client']);

            $permission = Permission::create(['name' => 'read role']);
            $permission = Permission::create(['name' => 'create role']);
            $permission = Permission::create(['name' => 'update role']);
            $permission = Permission::create(['name' => 'delete role']);

            $superAdmin->assignRole('superAdmin');
            $operator->assignRole('operator');
            $client->assignRole('client');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
