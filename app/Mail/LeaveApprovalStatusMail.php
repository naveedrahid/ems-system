<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApprovalStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $leaveApplication;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $leaveApplication, $status)
    {
        $this->user = $user;
        $this->leaveApplication = $leaveApplication;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.leave-approval-status')
            ->with([
                'employeeName' => $this->user->name,
                'leaveType' => $this->leaveApplication->leaveType->name,
                'totalDays' => $this->leaveApplication->total_leave,
                'status' => $this->status,
            ])->cc(['developer@pixelz360.com.au', 'hr@pixelz360.com.au']);
    }
}
