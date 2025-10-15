<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReportWarningMail;
use App\Mail\ReportAcceptedMail;
use App\Mail\ReportRejectedMail;
use App\Models\User;
use App\Models\Post;
use App\Models\Report;

class MailService
{
    /**
     * Envoyer un email d'avertissement pour signalement
     */
    public function sendReportWarning(User $user, Post $post, int $reportCount)
    {
        try {
            Mail::to($user->email)->send(new ReportWarningMail($user, $post, $reportCount));
            
            Log::info("Email d'avertissement envoyé avec succès", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'post_id' => $post->id,
                'report_count' => $reportCount
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi d'email", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'post_id' => $post->id,
                'report_count' => $reportCount,
                'error' => $e->getMessage()
            ]);

            return $this->fallbackNotification($user, $post, $reportCount);
        }
    }

    /**
     * Système de fallback si l'email échoue
     */
    private function fallbackNotification(User $user, Post $post, int $reportCount)
    {
        Log::critical("NOTIFICATION CRITIQUE - Email échoué", [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $user->name,
            'post_id' => $post->id,
            'post_title' => $post->title,
            'report_count' => $reportCount,
            'action_required' => $reportCount >= 5 ? 'Compte désactivé' : 'Avertissement'
        ]);

        \DB::table('failed_notifications')->insert([
            'user_id' => $user->id,
            'type' => 'report_warning',
            'data' => json_encode([
                'post_id' => $post->id,
                'report_count' => $reportCount
            ]),
            'created_at' => now()
        ]);

        return false;
    }

    /**
     * Envoyer un email de vérification d'email
     */
    public function sendEmailVerification(User $user)
    {
        try {
            Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail($user));
            
            Log::info("Email de vérification envoyé avec succès", [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email de vérification", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendPasswordReset(User $user)
    {
        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user));
            
            Log::info("Email de réinitialisation envoyé avec succès", [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email de réinitialisation", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer notification au signaleur - signalement accepté
     */
    public function sendReportAcceptedNotification(User $reporter, Report $report)
    {
        try {
            Mail::to($reporter->email)->send(new ReportAcceptedMail($reporter, $report));
            
            Log::info("Email signalement accepté envoyé avec succès", [
                'reporter_id' => $reporter->id,
                'reporter_email' => $reporter->email,
                'report_id' => $report->id,
                'post_id' => $report->post_id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email signalement accepté", [
                'reporter_id' => $reporter->id,
                'reporter_email' => $reporter->email,
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);

            return $this->fallbackReporterNotification($reporter, $report, 'accepted');
        }
    }

    /**
     * Envoyer notification au signaleur - signalement rejeté
     */
    public function sendReportRejectedNotification(User $reporter, Report $report)
    {
        try {
            Mail::to($reporter->email)->send(new ReportRejectedMail($reporter, $report));
            
            Log::info("Email signalement rejeté envoyé avec succès", [
                'reporter_id' => $reporter->id,
                'reporter_email' => $reporter->email,
                'report_id' => $report->id,
                'post_id' => $report->post_id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email signalement rejeté", [
                'reporter_id' => $reporter->id,
                'reporter_email' => $reporter->email,
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);

            return $this->fallbackReporterNotification($reporter, $report, 'rejected');
        }
    }

    /**
     * Système de fallback pour notifications signaleur
     */
    private function fallbackReporterNotification(User $reporter, Report $report, string $status)
    {
        Log::critical("NOTIFICATION CRITIQUE - Email signaleur échoué", [
            'reporter_id' => $reporter->id,
            'reporter_email' => $reporter->email,
            'reporter_name' => $reporter->name,
            'report_id' => $report->id,
            'post_id' => $report->post_id,
            'report_status' => $status,
            'admin_note' => $report->admin_decision_note
        ]);

        // Enregistrer l'échec pour reprise manuelle
        try {
            \DB::table('failed_notifications')->insert([
                'user_id' => $reporter->id,
                'type' => 'report_' . $status,
                'data' => json_encode([
                    'report_id' => $report->id,
                    'post_id' => $report->post_id,
                    'status' => $status
                ]),
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::emergency("Impossible d'enregistrer la notification échouée", [
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }

    /**
     * Tester la configuration email
     */
    public function testEmailConfiguration()
    {
        try {
            Mail::raw('Email de test depuis Makers Community', function ($message) {
                $message->to(config('mail.from.address'))
                        ->subject('Test de configuration email');
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Test email échoué: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email admin pour notifications importantes
     */
    public function notifyAdmins($subject, $message, $data = [])
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            try {
                Mail::raw($message, function ($mail) use ($admin, $subject) {
                    $mail->to($admin->email)
                         ->subject('[ADMIN] ' . $subject);
                });
            } catch (\Exception $e) {
                Log::error("Erreur notification admin", [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}