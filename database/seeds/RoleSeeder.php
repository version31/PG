<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \Spatie\Permission\Models\Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);

        \Spatie\Permission\Models\Role::create([
            'name' => 'provider',
            'guard_name' => 'api',
        ]);


       \Spatie\Permission\Models\Role::create([
            'name' => 'user',
            'guard_name' => 'api',
        ]);


    }
}
