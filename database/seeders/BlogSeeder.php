<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) {
            return;
        }

        // Categories representant les menus et sections du portail
        $catActualite = BlogCategory::firstOrCreate(
            ['slug' => 'actualite'],
            ['name' => 'Actualité', 'description' => 'Toute l\'actualité de la paroisse et de l\'Église.']
        );

        $catEnseignement = BlogCategory::firstOrCreate(
            ['slug' => 'enseignement'],
            ['name' => 'Enseignement', 'description' => 'Approfondissez votre foi par l\'enseignement doctrinal.']
        );

        $catEvenement = BlogCategory::firstOrCreate(
            ['slug' => 'evenement'],
            ['name' => 'Événement', 'description' => 'Vivez les moments forts de notre communauté.']
        );

        $catSpiritualite = BlogCategory::firstOrCreate(
            ['slug' => 'spiritualite'],
            ['name' => 'Spiritualité', 'description' => 'Méditations et ressources pour nourrir votre vie intérieure.']
        );

        $catAnnonce = BlogCategory::firstOrCreate(
            ['slug' => 'annonce'],
            ['name' => 'Annonce', 'description' => 'Flash infos et annonces paroissiales importantes.']
        );

        $catLumiere = BlogCategory::firstOrCreate(
            ['slug' => 'reviens-a-la-lumiere'],
            ['name' => 'Reviens à la lumière', 'description' => 'Témoignages et conseils pour sortir des ténèbres.']
        );

        // Article 1: Careme
        BlogPost::updateOrCreate(
            ['slug' => 'comprendre-le-careme-catholique'],
            [
                'title' => 'Comprendre le Carême Catholique : Un Chemin de Conversion',
                'content' => '<h2>Le temps du Carême : 40 jours pour revenir à Dieu</h2><p>Le Carême est un temps de préparation à la fête de Pâques, cœur de la foi chrétienne. C\'est un chemin de 40 jours marqué par la prière, le jeûne et le partage...</p><h3>Les trois piliers du Carême</h3><ul><li><strong>La Prière :</strong> Pour intensifier notre relation avec le Christ.</li><li><strong>Le Jeûne :</strong> Pour faire de la place en nous et nous détacher du superflu.</li><li><strong>L\'Aumône :</strong> Pour ouvrir nos cœurs à nos frères nécessiteux.</li></ul>',
                'excerpt' => 'Découvrez le sens profond des 40 jours de préparation à la fête de Pâques.',
                'user_id' => $admin->id,
                'blog_category_id' => $catEnseignement->id,
                'status' => 'published',
                'is_featured' => true,
                'featured_image' => 'blog/posts/lent.png',
                'published_at' => now(),
            ]
        );

        // Article 2: Pornographie
        BlogPost::updateOrCreate(
            ['slug' => 'comment-vaincre-la-pornographie'],
            [
                'title' => 'Comment Vaincre la Pornographie : Retrouver sa Liberté Intérieure',
                'content' => '<h2>Sortir de l\'addiction : Un combat pour la dignité</h2><p>L\'addiction à la pornographie est un mal silencieux qui brise des vies et des relations. Pourtant, la victoire est possible par la grâce de Dieu et des outils concrets...</p><h3>Étapes pour la guérison</h3><ol><li><strong>Reconnaître le problème :</strong> Cacher le mal lui donne du pouvoir.</li><li><strong>Fuir les occasions :</strong> Installer des filtres et éviter l\'isolement.</li><li><strong>La vie de prière :</strong> Demander chaque jour la force à l\'Esprit Saint.</li><li><strong>L\'accompagnement :</strong> En parler à un prêtre ou un conseiller de confiance.</li></ol>',
                'excerpt' => 'Des conseils spirituels et pratiques pour se libérer des chaînes de l\'addiction pornographique.',
                'user_id' => $admin->id,
                'blog_category_id' => $catLumiere->id,
                'status' => 'published',
                'is_featured' => true,
                'featured_image' => 'blog/posts/recovery.png',
                'published_at' => now(),
            ]
        );
    }
}
