<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reporter;
    public $report;
    public $post;

    public function __construct(User $reporter, Report $report)
    {
        $this->reporter = $reporter;
        $this->report = $report;
        $this->post = $report->post;
    }

    public function build()
    {
        return $this->view('emails.report-rejected')
                    ->subject('Votre signalement n\'a pas été retenu - YOWL Community')
                    ->with([
                        'reporterName' => $this->reporter->name,
                        'postTitle' => $this->post->title,
                        'reportReason' => $this->report->reason,
                        'adminNote' => $this->report->admin_decision_note,
                        'reportDate' => $this->report->created_at->format('d/m/Y à H:i')
                    ]);
    }
}