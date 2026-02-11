<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RegistrationActivity::create([
            'title' => 'PÈLERINAGE DES JEUNES 2026',
            'subtitle' => 'Un moment fort de foi et de fraternité pour la jeunesse.',
            'date' => '2026-03-15',
            'start_time' => '08:00',
            'end_time' => '17:00',
            'location' => 'Sanctuaire Marial d\'Arigbo, Dassa-Zoumè',
            'color' => 'blue',
            'is_active' => true
        ]);

        \App\Models\RegistrationActivity::create([
            'title' => 'PÈLERINAGE DES ENFANTS',
            'subtitle' => 'Découvrir la joie de l\'Evangile à travers le jeu et la prière.',
            'date' => '2026-04-12',
            'start_time' => '09:00',
            'end_time' => '16:30',
            'location' => 'Parvis de la Basilique, Cotonou',
            'color' => 'indigo',
            'is_active' => true
        ]);

        \App\Models\RegistrationActivity::create([
            'title' => 'PÈLERINAGE DES ADULTES',
            'subtitle' => 'Retraite spirituelle et recueillement pour les aînés.',
            'date' => '2026-05-24',
            'start_time' => '07:30',
            'end_time' => '18:00',
            'location' => 'Foyer de Charité, Ouidah',
            'color' => 'slate',
            'is_active' => true
        ]);
    }
}
