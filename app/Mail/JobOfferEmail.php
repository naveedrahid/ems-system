<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

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
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $header = View::make('emails.jobPortalEmails.pdfs.header')->render();
        $footer = View::make('emails.jobPortalEmails.pdfs.footer')->render();
        $content = View::make('emails.jobPortalEmails.pdfs.job-offer', [
            'candidate' => $this->candidate,
            'jobTitle' => $this->jobTitle,
            'jobOffer' => $this->jobOffer,
        ])->render();

        $html = $header . $content . $footer;

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        return $this->subject('Job Offer')
            ->view('emails.jobPortalEmails.job-offer')
            ->attachData($pdfOutput, "job-offer.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
