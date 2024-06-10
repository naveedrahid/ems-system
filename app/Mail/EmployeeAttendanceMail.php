<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmployeeAttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $checkInStatus;
    public $checkInTime;

    public function __construct(User $user, $checkInStatus, $checkInTime)
    {
        $this->user = $user;
        $this->checkInStatus = $checkInStatus;
        $this->checkInTime = $checkInTime;
    }

    public function build()
    {
        return $this->view('emails.attendance')
            ->with([
                'employeeName' => $this->user->name,
                'checkInStatus' => $this->checkInStatus,
                'checkInTime' => $this->checkInTime,
            ]);
    }
}
