<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Brand;
use App\Models\FranchiseOwner;
use Faker\Factory as Faker;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get existing brands and franchise owners
        $brands = Brand::all();
        $franchiseOwners = FranchiseOwner::all();

        foreach ($franchiseOwners as $franchiseOwner) {
            // Choose two specific brands for the fixed allocations
            $brand1 = $brands->random();
            $brand2 = $brands->random();
            
            // Ensure brand1 and brand2 are different
            while ($brand1->id === $brand2->id) {
                $brand2 = $brands->random();
            }

            // Create 3 stores with brand1
            foreach (range(1, 3) as $index) {
                Store::create([
                    'brand_id' => $brand1->id,
                    'number' => $faker->unique()->numberBetween(100, 999),
                    'address' => $faker->address,
                    'city' => $faker->city,
                    'state' => $faker->stateAbbr,
                    'zip_code' => $faker->postcode,
                    'franchise_owner_id' => $franchiseOwner->id,
                ]);
            }

            // Create 2 stores with brand2
            foreach (range(1, 2) as $index) {
                Store::create([
                    'brand_id' => $brand2->id,
                    'number' => $faker->unique()->numberBetween(100, 999),
                    'address' => $faker->address,
                    'city' => $faker->city,
                    'state' => $faker->stateAbbr,
                    'zip_code' => $faker->postcode,
                    'franchise_owner_id' => $franchiseOwner->id,
                ]);
            }

            // Create 5 stores with random brands
            foreach (range(1, 5) as $index) {
                Store::create([
                    'brand_id' => $brands->where('id', '!=', $brand1->id)->where('id', '!=', $brand2->id)->random()->id,
                    'number' => $faker->unique()->numberBetween(100, 999),
                    'address' => $faker->address,
                    'city' => $faker->city,
                    'state' => $faker->stateAbbr,
                    'zip_code' => $faker->postcode,
                    'franchise_owner_id' => $franchiseOwner->id,
                ]);
            }
        }
    }
}
