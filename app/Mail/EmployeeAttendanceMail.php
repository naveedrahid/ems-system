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
    public $checkStatus;
    public $checkTime;
    public $checkType;

    public function __construct(User $user, $checkStatus, $checkTime, $checkType)
    {
        $this->user = $user;
        $this->checkStatus = $checkStatus;
        $this->checkTime = $checkTime;
        $this->checkType = $checkType;
    }

    public function build()
    {
        
        return $this->view('emails.attendance')
            ->with([
                'employeeName' => $this->user->name,
                'checkStatus' => $this->checkStatus,
                'checkTime' => $this->checkTime,
                'checkType' => $this->checkType,
            ])->cc(['developer@pixelz360.com.au', 'hr@pixelz360.com.au']);
    }
}
