<?php

namespace App\Http\Controllers\JobPortalControllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\JobModels\Job;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with(['department','designation','shift'])->get();
        // dd($jobs);
        // $departments = Department::where('id', $jobs->department_id)->get();
        // $designations = Designation::pluck('designation_name', 'id');
        // $shifts = Shift::pluck('name', 'id');

        return view('job-portal.jobs.index', compact('jobs'));
    }

    public function showJobsCandidate()
    {
        $jobs = Job::all();

        $departments = Department::pluck('department_name', 'id');
        $designations = Designation::pluck('designation_name', 'id');
        $shifts = Shift::pluck('name', 'id');

        $encryptedJobIds = $jobs->mapWithKeys(function ($job) {
            return [$job->id => encrypt($job->id)];
        });

        return view('job-portal.jobs.show-candidate', compact('jobs', 'departments', 'designations', 'shifts', 'encryptedJobIds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $job = new Job();
        $route = route('jobs.store');
        $formMethod = 'POST';
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::pluck('designation_name', 'id')->map(function ($name, $id) {
            return [
                'name' => $name,
                'department_id' => Designation::find($id)->department_id,
            ];
        })->toArray();
        $shifts = Shift::pluck('name', 'id')->toArray();
        $createrName = auth()->user()->name;
        $employmentTypes = [
            '' => 'Select Employment Type',
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
        ];

        return view('job-portal.jobs.form', compact('job', 'route', 'formMethod', 'departments', 'designations', 'shifts', 'createrName', 'employmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $request->validate([
            'title' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'shift_id' => 'required',
            'employment_type' => 'required',
            'location' => 'required',
            'salary_range' => 'required',
            'closing_date' => 'required',
            'description' => 'required',
            'job_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('job_img')) {
            $uploadedFile = $request->file('job_img');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = 'job_images/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($uploadedFile));
            $filePath = 'storage/' . $filePath;
        }

        $job = Job::create([
            'created_by' => $user->id,
            'title' => $request->title,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'shift_id' => $request->shift_id,
            'employment_type' => $request->employment_type,
            'location' => $request->location,
            'salary_range' => $request->salary_range,
            'closing_date' => $request->closing_date,
            'description' => $request->description,
            'job_img' => $filePath,
        ]);

        // return redirect()->route('home')->with('message', 'Record created');
        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $route = route('jobs.update', $job->id);
        $formMethod = 'PUT';
        $departments = Department::pluck('department_name', 'id')->toArray();
        $designations = Designation::pluck('designation_name', 'id')->map(function ($name, $id) {
            return [
                'name' => $name,
                'department_id' => Designation::find($id)->department_id,
            ];
        })->toArray();
        $shifts = Shift::pluck('name', 'id')->toArray();
        $createrName = auth()->user()->name;
        $employmentTypes = [
            '' => 'Select Employment Type',
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
        ];

        return view('job-portal.jobs.form', compact('job', 'route', 'formMethod', 'departments', 'designations', 'shifts', 'createrName', 'employmentTypes'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'shift_id' => 'required|exists:shifts,id',
            'employment_type' => 'required|string|in:full_time,part_time',
            'location' => 'required|string|max:255',
            'salary_range' => 'required|string|max:255',
            'closing_date' => 'required|date',
            'description' => 'nullable|string',
            'job_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $job->title = $validatedData['title'];
        $job->department_id = $validatedData['department_id'];
        $job->designation_id = $validatedData['designation_id'];
        $job->shift_id = $validatedData['shift_id'];
        $job->employment_type = $validatedData['employment_type'];
        $job->location = $validatedData['location'];
        $job->salary_range = $validatedData['salary_range'];
        $job->closing_date = $validatedData['closing_date'];
        $job->description = $validatedData['description'];

        if ($request->hasFile('job_img')) {
            if ($job->job_img && Storage::disk('public')->exists(str_replace('storage/', '', $job->job_img))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $job->job_img));
            }

            $uploadedFile = $request->file('job_img');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = 'job_images/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($uploadedFile));

            $job->job_img = 'storage/' . $filePath;
        }

        $job->save();

        return response()->json(['message' => 'Job Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if ($job->job_img) {
            $imagePath = str_replace('storage/', 'public/', $job->job_img);
            Storage::delete($imagePath);
        }
        $job->delete();
        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
}
