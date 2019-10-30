<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@nethunk.com',
            'password' => Hash::make('123passWord#'),
            'role' => 'admin',
        ]);

        $role = Role::create(['name' => 'employee']);
        $permission = Permission::create(['name' => 'employee access']);
        $role = Role::create(['name' => 'agent']);
        $permission = Permission::create(['name' => 'agent access']);
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'admin access']);

        $user = User::find(1);
        $user->assignRole('admin');
        $user->givePermissionTo('admin access');
    }
}
