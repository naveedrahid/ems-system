<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\JobModels\Candidate;
use App\Models\JobModels\Job;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Candidate::with('scheduleInterviews')->paginate(15);
        $applicationStatuses = Candidate::candidate_application_status();

        return view('job-portal.candidates.index', compact('candidates', 'applicationStatuses'));
    }

    // public function getData()
    // {
    //     $candidates = Candidate::with('job')->select('candidates.*'); 

    //     return DataTables::of($candidates)
    //         ->addColumn('date', function ($candidate) {
    //             return $candidate->created_at->format('d M Y');
    //         })
    //         ->addColumn('name', function ($candidate) {
    //             return $candidate->name;
    //         })
    //         ->addColumn('email', function ($candidate) {
    //             return $candidate->email;
    //         })
    //         ->addColumn('gender', function ($candidate) {
    //             return $candidate->gender;
    //         })
    //         ->addColumn('status', function ($candidate) {
    //             return $candidate->application_status;
    //         })
    //         ->addColumn('job_title', function ($candidate) {
    //             return $candidate->job ? $candidate->job->title : 'N/A';
    //         })
    //         ->addColumn('manage', function ($candidate) {
    //             return '<a href="/candidates/' . $candidate->id . '" class="btn btn-xs btn-primary">View</a>';
    //         })
    //         ->rawColumns(['manage'])
    //         ->make(true);
    // }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $encryptedJobId = $request->query('job');

        $jobTitle = null;

        if ($encryptedJobId) {
            try {
                $jobId = decrypt($encryptedJobId);
                $job = Job::find($jobId);
                $jobTitle = $job ? $job->title : null;
            } catch (DecryptException $e) {
                abort(404);
            }
        }

        $candidate = new Candidate();
        $jobs = Job::pluck('id');
        $countries = Country::all()->pluck('name', 'id')->toArray();
        
        $cities = City::all()->groupBy('country_id')->map(function($cityGroup) {
            return $cityGroup->pluck('name', 'id');
        });

        return view('job-portal.candidates.create', compact('candidate', 'jobs', 'jobTitle', 'job', 'cities', 'countries'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'job_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'required|numeric|digits:11',
            'age' => 'required|integer|min:18',
            'country' => 'required',
            'city' => 'required',
            'gender' => 'required|string|max:10',
            'marital_status' => 'required|string',
            'total_experience' => 'required|integer|min:0',
            'current_salary' => 'required|numeric|min:0',
            'expected_salary' => 'required|numeric|min:0',
            'switching_reason' => 'required|string|max:255',
            'notice_period' => 'required|string|max:255',
            'datetime' => 'required|date',
            'linkdin' => 'required|url|max:255',
            'github' => 'required|url|max:255',
            'behance' => 'required|url|max:255',
            'website' => 'required|url|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            $jobId = decrypt($validatedData['job_id']);
        } catch (DecryptException $e) {
            return redirect()->back()->withErrors(['job_id' => 'Invalid job ID.']);
        }

        $candidate = new Candidate();

        if ($request->hasFile('resume')) {
            $uploadedFile = $request->file('resume');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('resumes', $fileName, 'public');
            $candidate->resume = 'storage/' . $filePath;
        }

        if ($request->hasFile('cover_letter')) {
            $uploadedFile = $request->file('cover_letter');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('cover_letters', $fileName, 'public');
            $candidate->cover_letter = 'storage/' . $filePath;
        }

        $candidate->job_id = $jobId;
        $candidate->is_applied = 1;
        $candidate->first_name = $validatedData['first_name'];
        $candidate->last_name = $validatedData['last_name'];
        $candidate->email = $validatedData['email'];
        $candidate->phone = $validatedData['phone'];
        $candidate->age = $validatedData['age'];
        $candidate->country = $validatedData['country'];
        $candidate->city = $validatedData['city'];
        $candidate->gender = $validatedData['gender'];
        $candidate->marital_status = $validatedData['marital_status'];
        $candidate->total_experience = $validatedData['total_experience'];
        $candidate->current_salary = $validatedData['current_salary'];
        $candidate->expected_salary = $validatedData['expected_salary'];
        $candidate->switching_reason = $validatedData['switching_reason'];
        $candidate->notice_period = $validatedData['notice_period'];
        $candidate->datetime = $validatedData['datetime'];
        $candidate->linkdin = $validatedData['linkdin'];
        $candidate->github = $validatedData['github'];
        $candidate->behance = $validatedData['behance'];
        $candidate->website = $validatedData['website'];
        $candidate->application_status = 'Pending';

        $candidate->save();

        // return redirect()->back()->with('message', 'Candidate created successfully.');
        return response()->json(['message' => 'Application submit successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateWithJob = Candidate::with('job')->find($id);
        $cityName = City::where('id', $candidateWithJob->city)->pluck('name')->first();
        $countryName = Country::where('id', $candidateWithJob->country)->pluck('name')->first();
        // dd($title);
        return view('job-portal.candidates.show', compact('candidateWithJob', 'cityName', 'countryName'));
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

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return response()->json(['message' => 'Candidate deleted successfully'], 200);
    }

    public function candidateStatus(Candidate $candidate)
    {
        $newStatus = request()->input('status');
        // dd($newStatus);
        $candidate->application_status = $newStatus;
        $candidate->save();
        return response()->json(['message' => 'Candidate status has been updated.'], 200);
    }

    public function checkEmailPhone(Request $request)
    {
        $emailExists = Candidate::where('email', $request->email)->exists();
        $phoneExists = Candidate::where('phone', $request->phone)->exists();
        return response()->json([
            'emailExists' => $emailExists,
            'phoneExists' => $phoneExists,
        ]);
    }
}
