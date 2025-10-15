<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // S'assurer qu'on a des posts et des utilisateurs
        $posts = Post::all();
        $users = User::where('role', 'user')->get(); // Seuls les users normaux peuvent signaler
        $admin = User::where('role', 'admin')->first(); // Récupérer l'admin
        
        if ($posts->isEmpty() || $users->isEmpty() || !$admin) {
            $this->command->warn('Pas assez de posts, d\'utilisateurs ou pas d\'admin pour créer des reports');
            return;
        }

        // Données réalistes pour les signalements
        $reportData = [
            [
                'reason' => 'spam',
                'description' => 'Ce post contient du spam publicitaire répétitif',
                'status' => 'pending',
            ],
            [
                'reason' => 'inappropriate',
                'description' => 'Contenu inapproprié et offensant',
                'status' => 'pending',
            ],
            [
                'reason' => 'harassment',
                'description' => 'Commentaires harcelants envers d\'autres utilisateurs',
                'status' => 'resolved',
                'admin_note' => 'Utilisateur averti, contenu supprimé',
                'resolved_at' => now()->subDays(2),
                'resolved_by' => $admin->id,
            ],
            [
                'reason' => 'fake',
                'description' => 'Informations fausses et trompeuses',
                'status' => 'rejected',
                'admin_note' => 'Signalement non fondé après vérification',
                'resolved_at' => now()->subDays(1),
                'resolved_by' => $admin->id,
            ],
            [
                'reason' => 'other',
                'description' => 'Violation des conditions d\'utilisation',
                'status' => 'pending',
            ],
            [
                'reason' => 'spam',
                'description' => null, // Parfois pas de description
                'status' => 'resolved',
                'admin_note' => 'Post supprimé pour spam',
                'resolved_at' => now()->subHours(6),
                'resolved_by' => $admin->id,
            ],
            [
                'reason' => 'inappropriate',
                'description' => 'Langage inapproprié dans les commentaires',
                'status' => 'pending',
            ],
            [
                'reason' => 'harassment',
                'description' => 'Harcèlement répété envers un utilisateur spécifique',
                'status' => 'resolved',
                'admin_note' => 'Compte temporairement suspendu',
                'resolved_at' => now()->subDays(3),
                'resolved_by' => $admin->id,
            ]
        ];

        $this->command->info('Création de ' . count($reportData) . ' signalements...');

        foreach ($reportData as $data) {
            // Sélectionner aléatoirement un post et un utilisateur
            $post = $posts->random();
            $user = $users->random();
            
            // Vérifier qu'un signalement n'existe pas déjà pour cette combinaison
            $existingReport = Report::where('post_id', $post->id)
                ->where('reporter_user_id', $user->id)
                ->first();
                
            if (!$existingReport) {
                Report::create([
                    'post_id' => $post->id,
                    'reporter_user_id' => $user->id,
                    'reason' => $data['reason'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'admin_note' => $data['admin_note'] ?? null,
                    'resolved_at' => $data['resolved_at'] ?? null,
                    'resolved_by' => isset($data['resolved_by']) ? $admin->id : null,
                    'created_at' => now()->subDays(rand(0, 30)), // Signalements sur 30 jours
                    'updated_at' => $data['resolved_at'] ?? now()->subDays(rand(0, 30)),
                ]);
            }
        }

        $this->command->info('Signalements créés avec succès !');
        
        // Afficher un résumé
        $summary = [
            'Total' => Report::count(),
            'Pending' => Report::where('status', 'pending')->count(),
            'Resolved' => Report::where('status', 'resolved')->count(),
            'Rejected' => Report::where('status', 'rejected')->count(),
        ];
        
        $this->command->table(['Statut', 'Nombre'], [
            ['Total', $summary['Total']],
            ['En attente', $summary['Pending']],
            ['Résolus', $summary['Resolved']],
            ['Rejetés', $summary['Rejected']],
        ]);
    }
}
