<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GroupParoissialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $admin = User::first();
        
        if (!$admin) {
            $this->command->error("Aucun utilisateur trouvé pour être le créateur des membres.");
            return;
        }

        $groupes = [
            'Chorale Sainte Cécile',
            'Légion de Marie',
            'Renouveau Charismatique',
            'CV-AV (Cœurs Vaillants - Âmes Vaillantes)',
            'Scoutisme Catholique',
            'JEC (Jeunesse Étudiante Catholique)',
            'Confrérie du Saint Sacrement',
            'Association des Femmes Catholiques',
            'Groupe des Lecteurs',
            'Enfants de Chœur'
        ];

        $responsabilites = [
            'Président',
            'Vice-Président',
            'Secrétaire',
            'Trésorier',
            'Membre actif',
            'Responsable spirituel',
            'Chargé de communication',
            'Membre d\'honneur'
        ];

        $situations = [
            'Celibataire',
            'Concubinage',
            'Marier',
            'Divorcer',
            'Veuve / Veuf'
        ];

        $professions = [
            'Enseignant',
            'Commerçant',
            'Médecin',
            'Ingénieur',
            'Artisan',
            'Étudiant',
            'Sans emploi',
            'Retraité',
            'Fonctionnaire',
            'Avocat',
            'Comptable',
            'Infirmier'
        ];

        foreach ($groupes as $nomGroupe) {
            $group = Group::firstOrCreate([
                'nom_groupe' => $nomGroupe
            ]);

            // Delete existing members to have fresh data with new fields
            $group->members()->delete();

            $nombreMembres = rand(12, 15);

            for ($i = 0; $i < $nombreMembres; $i++) {
                GroupMember::create([
                    'nom_prenom' => $faker->name,
                    'date_naissance' => $faker->dateTimeBetween('-70 years', '-10 years'),
                    'date_adhesion' => $faker->dateTimeBetween('-5 years', 'now'),
                    'responsabilite' => ($i === 0) ? 'Président' : $faker->randomElement($responsabilites),
                    'situation_professionnelle' => $faker->randomElement($professions),
                    'nombre_enfant' => rand(0, 6),
                    'situation_matrimoniale' => $faker->randomElement($situations),
                    'group_id' => $group->id,
                    'created_by' => $admin->id,
                    'photo' => null,
                ]);
            }
        }

        $this->command->info("10 groupes paroissiaux et leurs membres ont été créés avec succès.");
    }
}
