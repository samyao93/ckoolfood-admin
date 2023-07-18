<?php

namespace Database\Seeders;

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
        // \App\Models\Vendor::factory(10000)->create();
        // \App\Models\Restaurant::factory(10000)->create();
        $this->call([
            UserSeeder::class
        ]);
    }
}
