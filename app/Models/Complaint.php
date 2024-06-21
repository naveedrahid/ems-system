<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'process';
    const STATUS_RESOLVED = 'resolved';
    const TECHNICAL_ISSUE = 'technical_issue';
    const HARASSMENT_ISSUE = 'harassment_issue';
    const TANSPORT_ISSUE = 'transport_issue';
    const BANK_DETAIL_ISSUE = 'bank_detail_issue';
    const MEDICAL_ISSUE = 'medical_issue';
    const OTHERS = 'others';

    protected $fillable = ['user_id', 'employee_id', 'ticket_number', 'complaint_status', 'complaint_type', 'content'];

    protected $attributes = [
        'complaint_status' => self::STATUS_PENDING,
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
        ];
    }

    public static function get_complaint_types()
    {
        return [
            self::TECHNICAL_ISSUE,
            self::HARASSMENT_ISSUE,
            self::TANSPORT_ISSUE,
            self::BANK_DETAIL_ISSUE,
            self::MEDICAL_ISSUE,
            self::OTHERS,
        ];
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
