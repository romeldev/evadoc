<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Permission::class, 30)->create();
        Permission::query()->delete();
        Permission::insert($this->data());
    }

    public function data()
    {
        $now = \Carbon\carbon::now();

        return [
            [ 'name' => 'users.create', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'users.edit', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'users.delete', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'users.show', 'guard_name'=> 'web', 'created_at' => $now ],

            [ 'name' => 'surveys.create', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'surveys.edit', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'surveys.delete', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'surveys.show', 'guard_name'=> 'web', 'created_at' => $now ],

            [ 'name' => 'evaluations.create', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'evaluations.edit', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'evaluations.delete', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'evaluations.show', 'guard_name'=> 'web', 'created_at' => $now ],

            [ 'name' => 'reports.create', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'reports.edit', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'reports.delete', 'guard_name'=> 'web', 'created_at' => $now ],
            [ 'name' => 'reports.show', 'guard_name'=> 'web', 'created_at' => $now ],

        ];
    }
}
