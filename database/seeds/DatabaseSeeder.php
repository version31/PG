<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(RoleSeeder::class);
//         $this->call(VariableSeed::class);
//         $this->call(PermissionSeed::class);
//         $this->call(MenuSeeder::class);
         $this->call(BannerPlanSeed::class);
    }
}
