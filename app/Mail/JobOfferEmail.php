<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobOfferEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $jobTitle;
    public $jobOffer;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($candidate, $jobTitle, $jobOffer)
    {
        $this->candidate = $candidate;
        $this->jobTitle = $jobTitle;
        $this->jobOffer = $jobOffer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = Pdf::loadView('emails.jobPortalEmails.pdfs.job-offer', [
            'candidate' => $this->candidate,
            'jobTitle' => $this->jobTitle,
            'jobOffer' => $this->jobOffer,
        ])->output();

        return $this->subject('Job Offer')
            ->view('emails.jobPortalEmails.job-offer')
            ->attachData($pdf, "job-offer.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
