<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission; // use your custom model!

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create user',
            'view user',
            'update user',
            'delete user',
            'activate user',
            'deactivate user',
            'create role',
            'view role',
            'update role',
            'delete role',
            'create permission',
            'view permission',
            'update permission',
            'delete permission',
            'view session',
            'delete session',
            'view backup',
            'delete backup',
        ];

        foreach ($permissions as $permissionName) {
            $permission = new Permission([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
            $permission->save(); // Triggers HasSlug trait
        }
    }
}
