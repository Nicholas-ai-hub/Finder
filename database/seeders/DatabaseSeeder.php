<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Listing;
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
        //Create single user
        $user = User::factory()->create([
            'name' => 'Johnny Depp',
            'email' => 'test@example.com',
        ]);

        //Create 7 listings which belongs to only one user
        Listing::factory(7)->create([
            'user_id' => $user->id
        ]);
    }
}
