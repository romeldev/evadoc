<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select('delete from users');
        DB::select('delete from model_has_roles');
        DB::select('delete from role_has_permissions');

        factory(User::class)->create(['name'=> 'rodira', 'email'=>env('ADMIN_EMAIL', 'admin@example.com'), 'school_code'=>24, 'school_name'=>'Ingeniería de Sistemas' ]);

        factory(User::class, 30)->create();

        $this->assignPermissionsToRoles();

        $this->assignRolesToUsers();
    }

    public function assignRolesToUsers()
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminRole = env('ADMIN_ROLE', 'admin');

        $rolesAll = Role::where('name', '<>', $adminRole)->get();
        $users = User::where('email', '<>', $adminEmail)->get();

        foreach($users as $user){
            $roles = $rolesAll->random( rand(1, $rolesAll->count() ) );
            $user->assignRole( $roles->pluck('name')->toArray() );
        }

        $userAdmin = User::where('email', $adminEmail)->first();
        $userAdmin->assignRole($adminRole);
    }

    public function assignPermissionsToRoles()
    {
        $adminRole = env('ADMIN_ROLE', 'admin');

        $roles = Role::where('name', '<>', $adminRole)->get();
        $permissionAll = Permission::all();

        foreach($roles as $role)
        {
            $permissions = $permissionAll->random( rand(1, $permissionAll->count()) );
            $role->givePermissionTo($permissions->pluck('name')->toArray());
        }

        $roleAdmin =Role::where('name', $adminRole)->first();
        $roleAdmin->givePermissionTo($permissionAll->pluck('name')->toArray());
    }
}
