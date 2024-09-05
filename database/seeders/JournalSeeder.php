<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;
use App\Models\Store;
use Faker\Factory as Faker;

class JournalSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get existing stores
        $stores = Store::all();

        foreach ($stores as $store) {
            // Generate a random number of journal entries for each store (e.g., 1 to 10 entries per store)
            foreach (range(1, $faker->numberBetween(1, 10)) as $index) {
                Journal::create([
                    'store_id' => $store->id,
                    'date' => $faker->date,
                    'revenue' => $faker->randomFloat(2, 1000, 10000), // Random revenue between 1000 and 10000
                    'food_cost' => $faker->randomFloat(2, 100, 5000), // Random food cost between 100 and 5000
                    'labor_cost' => $faker->randomFloat(2, 100, 5000), // Random labor cost between 100 and 5000
                    'profit' => $faker->randomFloat(2, 100, 5000), // Random profit between 100 and 5000
                ]);
            }
        }
    }
}
