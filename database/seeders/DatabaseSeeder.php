<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::create([
            'role_name' => 'SuperAdmin',
        ]);

        Role::create([
            'role_name' => 'Operator',
        ]);

        Role::create([
            'role_name' => 'Client',
        ]);

        $this->call(UserRolePermissionSeeder::class);

    }
}
