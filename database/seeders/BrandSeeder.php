<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            'McDonald\'s',
            'Burger King',
            'Wendy\'s',
            'KFC',
            'Taco Bell',
            'Subway',
            'Pizza Hut',
            'Domino\'s',
            'Sonic Drive-In',
            'Chick-fil-A'
        ];

        $namedColors = pick_color(); // Get the array of named colors

        foreach ($brands as $brandName) {
            $color = $this->generateRandomColor($namedColors);
            Brand::create([
                'name' => $brandName,
                'color' => $color,
            ]);
        }
    }

    /**
     * Generate a random hex color from named colors.
     *
     * @param array $namedColors
     * @return string
     */
    private function generateRandomColor($namedColors)
    {
        // Pick a random color from the named colors array
        $colors = array_keys($namedColors);
        return $colors[array_rand($colors)];
    }
}
