<?php

namespace Database\Seeders;

use App\Enum\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Root',
            'email' => 'root@root.com',
            'password' => Hash::make('root')
        ]);
        $user->assignRole(Role::findByName(Roles::ADMIN, 'api'));
    }
}
