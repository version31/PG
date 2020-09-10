<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Permission::create(['name' => 'users-read']);
        Permission::create(['name' => 'users-insert']);
        Permission::create(['name' => 'users-update']);
        Permission::create(['name' => 'users-delete']);

        Permission::create(['name' => 'providers-read']);
        Permission::create(['name' => 'providers-insert']);
        Permission::create(['name' => 'providers-update']);
        Permission::create(['name' => 'providers-delete']);

        Permission::create(['name' => 'pages-read']);
        Permission::create(['name' => 'pages-insert']);
        Permission::create(['name' => 'pages-update']);
        Permission::create(['name' => 'pages-delete']);


        Permission::create(['name' => 'posts-read']);
        Permission::create(['name' => 'posts-insert']);
        Permission::create(['name' => 'posts-update']);
        Permission::create(['name' => 'posts-delete']);


        Permission::create(['name' => 'products-read']);
        Permission::create(['name' => 'products-insert']);
        Permission::create(['name' => 'products-update']);
        Permission::create(['name' => 'products-delete']);

        Permission::create(['name' => 'categories-read']);
        Permission::create(['name' => 'categories-insert']);
        Permission::create(['name' => 'categories-update']);
        Permission::create(['name' => 'categories-delete']);


        Permission::create(['name' => 'services-read']);
        Permission::create(['name' => 'services-insert']);
        Permission::create(['name' => 'services-update']);
        Permission::create(['name' => 'services-delete']);

        Permission::create(['name' => 'stories-read']);
        Permission::create(['name' => 'stories-insert']);
        Permission::create(['name' => 'stories-update']);
        Permission::create(['name' => 'stories-delete']);

        Permission::create(['name' => 'plans-read']);
        Permission::create(['name' => 'plans-insert']);
        Permission::create(['name' => 'plans-update']);
        Permission::create(['name' => 'plans-delete']);
    }
}
