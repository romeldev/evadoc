<?php

use Illuminate\Database\Seeder;
use App\Model\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::query()->delete();

        Menu::insert( $this->data() );
    }

    public function data()
    {
        $items = [];

        // dashboard
        $items[] = [
            'id' => 1,
            'order' => 2,
            'label' => 'dashboard',
            'path' => '/dashboard',
            'icon' => 'fas fa-tachometer-alt blue',
            'menu_id' => null,
        ];

        // $scales
        $items[] = [
            'id' => 2,
            'order' => 2,
            'label' => 'scales',
            'path' => '/scales',
            'icon' => 'fas fa-ruler-combined orange',
            'menu_id' => null,
        ];


        // $level
        $items[] = [
            'id' => 3,
            'order' => 3,
            'label' => 'levels',
            'path' => '/levels',
            'icon' => 'fas fa-layer-group pink',
            'menu_id' => null,
        ];

        // $surveys
        $items[] = [
            'id' => 4,
            'order' => 4,
            'label' => 'surveys',
            'path' => '/surveys',
            'icon' => 'fas fa-poll green',
            'menu_id' => null,
        ];

        // $evaluations
        $items[] = [
            'id' => 5,
            'order' => 5,
            'label' => 'evaluations',
            'path' => '/evaluations',
            'icon' => 'fas fa-file-alt yellow',
            'menu_id' => null,
        ];

        // $reports
        $items[] = [
            'id' => 6,
            'order' => 6,
            'label' => 'reports',
            'path' => '/reports',
            'icon' => 'fas fa-file-alt yellow',
            'menu_id' => null,
        ];

        // $profile
        $items[] = [
            'id' => 7,
            'order' => 7,
            'label' => 'profile',
            'path' => '/profile',
            'icon' => 'fas fa-user orange',
            'menu_id' => null
        ];

        // $settings
        $items[] = [
            'id' => 8,
            'order' => 8,
            'label' => 'settings',
            'path' => '/settings',
            'icon' => 'fas fa-cog cyan',
            'menu_id' => null,
        ];

        // $logout
        $items[] = [
            'id' => 9,
            'order' => 9,
            'label' => 'logout',
            'path' => '/logout',
            'icon' => 'fas fa-power-off red',
            'menu_id' => null
        ];



        // Level 2
        // $users
        $items[] = [
            'id' => 10,
            'order' => 1,
            'label' => 'users',
            'path' => '/settings/users',
            'icon' => 'fas fa-users',
            'menu_id' => 8,
        ];


        // Roles
        $items[] = [
            'id' => 11,
            'order' => 2,
            'label' => 'roles',
            'path' => '/settings/roles',
            'icon' => 'fas fa-tags',
            'menu_id' => 8,
        ];
        
        // Permissions
        $items[] = [
            'id' => 12,
            'order' => 3,
            'label' => 'permissions',
            'path' => '/settings/permissions',
            'icon' => 'fas fa-key',
            'menu_id' => 8,
        ];

        // Menus
        $items[] = [
            'id' => 13,
            'order' => 4,
            'label' => 'menus',
            'path' => '/settings/menus',
            'icon' => 'fas fa-circle',
            'menu_id' => 8,
        ];

        // exclusions
        $items[] = [
            'id' => 14,
            'order' => 5,
            'label' => 'exclusions',
            'path' => '/settings/exclusions/teachers',
            'icon' => 'fas fa-circle',
            'menu_id' => 8,
        ];

        // System
        $items[] = [
            'id' => 15,
            'order' => 6,
            'label' => 'system',
            'path' => '/settings/system',
            'icon' => 'fas fa-desktop',
            'menu_id' => 8,
        ];

        return $items;
    }
}
