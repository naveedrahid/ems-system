<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Models\JobModels\InterviewerRemark;
use Illuminate\Http\Request;

class InterviewerRemarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $interviewer_remarks = InterviewerRemark::with(['scheduleInterview', 'job', 'user', 'candidate'])
            ->where('interviewer_id', $userId)
            ->get();

        return view('job-portal.interviewer-remarks.index', compact('interviewer_remarks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InterviewerRemark $interviewer_remark)
    {
        // $interviewer_remark = InterviewerRemark::findOrFail($id);
        return view('job-portal.interviewer-remarks.index', compact('interviewer_remark'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InterviewerRemark $interviewer_remark)
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
    public function update(Request $request, InterviewerRemark $interviewer_remark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InterviewerRemark $interviewer_remark)
    {
        //
    }

    public function candidateStatus(Request $request, InterviewerRemark $interviewer_remark)
    {
        $statuses = ['Pending', 'Selected', 'Rejected'];
        $currentStatus = $interviewer_remark->status;
        $nextStatus = $statuses[(array_search($currentStatus, $statuses) + 1) % 3]; // Determine the next status

        $interviewer_remark->status = $nextStatus;
        $interviewer_remark->save();

        return response()->json(['status' => $nextStatus]);
    }

    public function selectedCandidateRemarks(Request $request, $id)
    {
        $interviewer_remarks = InterviewerRemark::findOrFail($id);

        $request->validate([
            'selected_notes' => 'required',
        ]);

        $interviewer_remarks->selected_notes = $request->selected_notes;
        $interviewer_remarks->save();

        return response()->json(['message' => 'Selected Notes updated successfully.']);
    }

    public function rejectedCandidateRemarks(Request $request, $id)
    {
        $interviewer_remarks = InterviewerRemark::findOrFail($id);

        $request->validate([
            'rejected_notes' => 'required',
        ]);

        $interviewer_remarks->rejected_notes = $request->rejected_notes;
        $interviewer_remarks->save();

        return response()->json(['message' => 'Rejected Notes updated successfully.']);
    }
}
