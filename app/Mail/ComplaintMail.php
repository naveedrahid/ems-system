<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $complaint;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $complaint, $type)
    {
        $this->user = $user;
        $this->complaint = $complaint;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.complaints')->with([
            'employeeName' => $this->user->name,
            'ticketNumber' => $this->complaint->ticket_number,
            'content' => $this->complaint->content,
            'complaintStatus' => $this->complaint->complaint_status,
            'type' => $this->type,
        ])->cc(['developer@pixelz360.com.au', 'hr@pixelz360.com.au']);
    }
}
