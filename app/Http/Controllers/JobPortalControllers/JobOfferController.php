<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Models\JobModels\Candidate;
use App\Models\JobModels\Job;
use App\Models\JobModels\JobOffer;
use App\Models\JobModels\ScheduleInterview;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $job_offer = new JobOffer();
        $interviewRemarks = ScheduleInterview::where('interview_type', 'Final')->first();
    
        $candidates = Candidate::where('id', $interviewRemarks->candidate_id)
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->mapWithKeys(function ($candidate) {
                return [$candidate->id => $candidate->first_name . ' ' . $candidate->last_name];
            });
    
        $job = Job::where('id', $interviewRemarks->job_id)->pluck('title', 'id');
        $route = route('job-offers.store');
        $formMethod = 'POST';
    
        return view('job-portal.job-offers.form', compact('job_offer', 'route', 'formMethod', 'job', 'candidates'));
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
