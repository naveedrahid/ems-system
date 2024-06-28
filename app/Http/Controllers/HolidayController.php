<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidays = Holiday::orderBy('id', 'DESC')->get();
        return view('holidays.index', compact('holidays'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Holiday $holiday)
    {
        $holiday = new Holiday();
        $route = route('holidays.store');
        $formMethod = 'POST';
        return view('holidays.form', compact('holiday', 'route', 'formMethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'in:active,deactive|default:deactive',
            'date' => 'required',
            'holiday_type'=> 'required|:in' . implode(',', array_keys(Holiday::getStatusOptions())),
        ]);

        Holiday::create($validate);
        return response()->json(['message' => 'Holiday Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        $holiday->date_range = $holiday->date ?: 'Invalid date - Invalid date';
        $route = route('holidays.update', $holiday->id);
        $formMethod = 'PUT';
        return view('holidays.form', compact('holiday', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable|string|max:255',
            'date' => 'required',
            'status' => 'nullable',
            'holiday_type' => 'required|in:' . implode(',', array_keys(Holiday::getStatusOptions())),
        ]);
    
        $holiday->update($request->all());
    
        return response()->json(['message' => 'Holiday Updated Successfully']);
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return response()->json(['message' => 'Holiday Deleted Successfully'],200);
    }

    public function updateStatus(Request $request, Holiday $holiday)
    {
        $holiday->status = $request->status;
        $holiday->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
