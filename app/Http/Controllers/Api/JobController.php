<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobModels\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function createJob(Request $request)
    {
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

        Job::create([
            'created_by' => auth()->user()->id,
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

        return response()->json(['message' => 'Job created successfully'], 200);
    }
}
