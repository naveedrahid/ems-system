<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobModels\Candidate;
use App\Models\JobModels\InterviewerRemark;
use App\Models\JobModels\Job;
use App\Models\JobModels\ScheduleInterview;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleInterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule_interviews = ScheduleInterview::with(['interviewerRemarks', 'job', 'candidate', 'user'])->orderBy('id', 'DESC')->get();
        return view('job-portal.schedule-interviews.index', compact('schedule_interviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schedule_interview = new ScheduleInterview();
        $route = route('schedule-interviews.store');
        $formMethod = 'POST';
        $departments = Department::pluck('department_name', 'id')->toArray();
        $employees = Employee::with(['department', 'user'])->get();
        $job = Job::pluck('title', 'id')->toArray();
        $candidate = Candidate::with('job')->where('application_status', 'Selected')->get();
        $interview_types = ScheduleInterview::interviewTypes();

        return view('job-portal.schedule-interviews.form', compact('schedule_interview', 'route', 'formMethod', 'departments', 'employees', 'candidate', 'job', 'interview_types'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'job_id' => 'required',
            'candidate_id' => 'required',
            'interviewer_id' => 'required',
            'interview_types' => 'required',
            'interviewer_notes' => 'required',
            'candidate_notes' => 'required',
            'interview_date' => 'required',
            'interview_time' => 'required',
        ]);

        $schedule_interview = new ScheduleInterview();
        $schedule_interview->job_id = $validateData['job_id'];
        $schedule_interview->candidate_id = $validateData['candidate_id'];
        $schedule_interview->interviewer_id = $validateData['interviewer_id'];
        $schedule_interview->interview_type = $validateData['interview_types'];
        $schedule_interview->interviewer_notes = $validateData['interviewer_notes'];
        $schedule_interview->candidate_notes = $validateData['candidate_notes'];
        $schedule_interview->interview_date = $validateData['interview_date'];
        $schedule_interview->interview_time = $validateData['interview_time'];

        $types = $validateData['interview_types'];
        $schedule_interview->interview_level = ($types === 'initial') ? 1 : ($types === 'technical' ? 2 : 3);

        $schedule_interview->save();

        $interviewer_remark = new InterviewerRemark();
        $interviewer_remark->schedule_interview_id = $schedule_interview->id;
        $interviewer_remark->job_id = $validateData['job_id'];
        $interviewer_remark->candidate_id = $validateData['candidate_id'];
        $interviewer_remark->interviewer_id = $validateData['interviewer_id'];
        $interviewer_remark->status = 'Pending';

        $interviewer_remark->save();

        return response()->json(['message' => 'Interview scheduale successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleInterview $schedule_interview)
    {
        $candidate = Candidate::where('id', $schedule_interview->candidate_id)->first();

        return view('job-portal.schedule-interviews.show', compact('schedule_interview', 'candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ScheduleInterview $schedule_interview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleInterview $schedule_interview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleInterview $schedule_interview)
    {
        //
    }

    public function interviewerRemarks(ScheduleInterview $schedule_interview)
    {
        $interviewer_remarks = InterviewerRemark::where('schedule_interview_id', $schedule_interview->id)->get();
    
        return view('job-portal.schedule-interviews.remarks', compact('schedule_interview', 'interviewer_remarks'));
    }
    
    
}
