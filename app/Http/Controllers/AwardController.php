<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $awards = Award::with('user')->get();
        return view('awards.index', compact('awards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $employees = Employee::with('user')->get();
        $award = new Award();
        $route = route('awards.store');
        $formMethod = 'POST';
        return view('awards.form', compact('departments', 'employees', 'award', 'route', 'formMethod'));
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
            'user_id' => 'required',
            'award_name' => 'required',
            'award_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
        ]);

        $awardFilePath = null;
        if ($request->hasFile('award_file')) {
            $file = $request->file('award_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'award_files/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($file));
            $awardFilePath = 'storage/' . $filePath;
        }

        $award = new Award();
        $award->user_id = $validatedData['user_id'];
        $award->award_name = $validatedData['award_name'];
        $award->award_file = $awardFilePath;
        $award->description = $validatedData['description'];
        $award->save();

        return response()->json(['message' => 'Award created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show(Award $award)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit(Award $award)
    {
        $departments = Department::all();
        $employees = Employee::with('user')->get();
        $route = route('awards.update', $award->id);
        $formMethod = 'PUT';
        return view('awards.form', compact('departments', 'employees', 'award', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Award $award)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'award_name' => 'required',
            'award_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
        ]);

        if ($request->hasFile('award_file')) {
            $file = $request->file('award_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'award_files/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($file));
            $awardFilePath = 'storage/' . $filePath;

            if ($award->award_file) {
                Storage::disk('public')->delete(str_replace('storage/', '', $award->award_file));
            }

            $award->award_file = $awardFilePath;
        }

        $award->user_id = $validatedData['user_id'];
        $award->award_name = $validatedData['award_name'];
        $award->description = $validatedData['description'];
        $award->save();

        return response()->json(['message' => 'Award updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function destroy(Award $award)
    {
        $award->delete();
        return response()->json(['message' => 'Award deleted successfully'], 200);
    }
}
