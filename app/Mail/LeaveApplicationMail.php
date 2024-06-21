<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $leaveApplication;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $leaveApplication)
    {
        $this->user = $user;
        $this->leaveApplication = $leaveApplication;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $startDate = null;
        $endDate = null;
        if ($this->leaveApplication) {
            $startDate = $this->leaveApplication->start_date;
            $endDate = $this->leaveApplication->end_date;
        }

        return $this->view('emails.leave-applications')
            ->with([
                'employeeName' => $this->user->name,
                'leaveType' => $this->leaveApplication->leaveType->name,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'reason' => $this->leaveApplication->reason,
                'totalDays' => $this->leaveApplication->total_leave,
            ])
            ->cc(['developer@pixelz360.com.au', 'hr@pixelz360.com.au']);
    }
}
