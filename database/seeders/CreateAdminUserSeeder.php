<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'ahmad mahmoud',
            'email' => 'ahmad.mahmoud@yahoo.com',
            'password' => bcrypt('12345678'),
            'role_name' => ["owner"],
            'status' => 'مفعل',
            ]);

            $role = Role::create(['name' => 'owner']);

            $permissions = Permission::pluck('id','id')->all();

            $role->syncPermissions($permissions);

            $user->assignRole([$role->id]);
        }
}
