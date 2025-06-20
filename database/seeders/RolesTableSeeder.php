<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // ✅ your custom model
use App\Models\Permission; // same for permissions

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'user',
        ];

        foreach ($roles as $roleName) {
            $role = new Role([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
            $role->save(); // triggers slug generation

            if ($roleName === 'super-admin') {
                $role->syncPermissions(Permission::all());
            }
        }
    }
}
