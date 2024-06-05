<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
