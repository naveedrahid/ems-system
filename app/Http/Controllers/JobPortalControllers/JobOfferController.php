<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Mail\JobOfferEmail;
use App\Models\JobModels\Candidate;
use App\Models\JobModels\InterviewerRemark;
use App\Models\JobModels\Job;
use App\Models\JobModels\JobOffer;
use App\Models\JobModels\ScheduleInterview;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $job_offers = JobOffer::with(['job', 'candidate'])->get();
        return view('job-portal.job-offers.index', compact('job_offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $interviewRemarks = ScheduleInterview::with('interviewerRemarks')
            ->where('interview_type', 'final')
            ->whereHas('interviewerRemarks', function ($query) {
                $query->where('status', 'Selected');
            })
            ->get();

        $candidate_ids = $interviewRemarks->pluck('interviewerRemarks')->flatten()->pluck('candidate_id')->unique();
        $job_ids = $interviewRemarks->pluck('interviewerRemarks')->flatten()->pluck('job_id')->unique();

        $candidates = Candidate::whereIn('id', $candidate_ids)
            ->select('id', 'first_name', 'last_name', 'job_id')
            ->get()
            ->mapWithKeys(function ($candidate) {
                return [$candidate->id => ['name' => $candidate->first_name . ' ' . $candidate->last_name, 'job_id' => $candidate->job_id]];
            });

        $jobs = Job::whereIn('id', $job_ids)->pluck('title', 'id');

        $job_offer = new JobOffer();
        $route = route('job-offers.store');
        $formMethod = 'POST';
        $selectedJobId = null;
        return view('job-portal.job-offers.form', compact('job_offer', 'route', 'formMethod', 'jobs', 'candidates', 'selectedJobId'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'candidate_id' => 'required',
            'candidate_offer' => 'required',
            'candidate_salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();

        JobOffer::create($validatedData);

        return response()->json(['message' => 'Job offer send successfully'], 200);
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
    public function edit(JobOffer $job_offer)
    {
        $interviewRemarks = ScheduleInterview::with('interviewerRemarks')
            ->where('interview_type', 'final')
            ->whereHas('interviewerRemarks', function ($query) {
                $query->where('status', 'Selected');
            })
            ->get();

        $candidate_ids = $interviewRemarks->pluck('interviewerRemarks')->flatten()->pluck('candidate_id')->unique();
        $job_ids = $interviewRemarks->pluck('interviewerRemarks')->flatten()->pluck('job_id')->unique();

        $candidates = Candidate::whereIn('id', $candidate_ids)
            ->select('id', 'first_name', 'last_name', 'job_id')
            ->get()
            ->mapWithKeys(function ($candidate) {
                return [$candidate->id => ['name' => $candidate->first_name . ' ' . $candidate->last_name, 'job_id' => $candidate->job_id]];
            });

        $jobs = Job::whereIn('id', $job_ids)->pluck('title', 'id');

        $route = route('job-offers.update', $job_offer->id);
        $formMethod = 'PUT';
        $selectedJobId = null;
        return view('job-portal.job-offers.form', compact('job_offer', 'route', 'formMethod', 'jobs', 'candidates', 'selectedJobId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobOffer $job_offer)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'candidate_id' => 'required',
            'candidate_offer' => 'required',
            'candidate_salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();

        $job_offer->update($validatedData);

        return response()->json(['message' => 'Job offer update successfully'], 200);
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

    public function sendEmail(Request $request, JobOffer $job_offer)
    {
        try {
            $candidate = $job_offer->candidate;
            $candidateDetails = (object) [
                'name' => $candidate->first_name . ' ' . $candidate->last_name,
                'email' => $candidate->email,
            ];
            $jobTitle = $job_offer->job->title;
    
            // Send the email
            Mail::to($candidateDetails->email)->send(new JobOfferEmail($candidateDetails, $jobTitle, $job_offer));
    
            return response()->json(['message' => 'Job offer sent successfully!']);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Email send error: ' . $e->getMessage());
    
            // Return error response
            return response()->json(['message' => 'Failed to send job offer email.'], 500);
        }
    }
    
}

