<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movements = [
            'Groupe de prière',
            'Chorale',
            'Catéchisme',
        ];

        foreach ($movements as $name) {
            \App\Models\Movement::firstOrCreate(['name' => $name]);
        }
    }
}
