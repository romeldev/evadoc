<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->delete();
        Role::insert( $this->data() );
        // DB::table('roles')->insert($this->data());
    }

    public function data()
    {
        $now = \Carbon\carbon::now();

        return [
            [ 'name' => 'admin', 'guard_name' => 'web', 'created_at' => $now ],
            [ 'name' => 'headmaster', 'guard_name' => 'web', 'created_at' => $now ],
            [ 'name' => 'assistant', 'guard_name' => 'web', 'created_at' => $now ],
            [ 'name' => 'teacher', 'guard_name' => 'web', 'created_at' => $now ],
        ];
    }
}
