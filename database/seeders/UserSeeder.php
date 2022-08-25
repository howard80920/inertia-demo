<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create([
            'name' => 'Lucas Yang',
            'email' => 'yangchenshin77@gmail.com',
            'description' => '夜空中的小星星，也會閃耀著光芒~~',
        ]);

        \App\Models\User::factory(9)->create();
    }
}
