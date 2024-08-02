<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::all();
        $shifts->transform(function ($shift) {
            $shift->opening =  Carbon::parse($shift->opening)->format('g:i A');
            $shift->closing =  Carbon::parse($shift->closing)->format('g:i A');
            return $shift;
        });
        return view('shifts.index', compact('shifts'));
    }

    public function getShiftData()
    {
        $shifts = Shift::all();
        if ($shifts->isEmpty()) {
            return response()->json(['message' => 'No shifts found'], 404);
        }
    
        $shifts->transform(function ($shift) {
            $shift->opening = Carbon::parse($shift->opening)->format('g:i A');
            $shift->closing = Carbon::parse($shift->closing)->format('g:i A');
            return $shift;
        });
        return response()->json($shifts);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shift = new Shift();
        $route = route('shifts.store');
        $formMethod = 'POST';
        return view('shifts.form', compact('shift', 'route', 'formMethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'opening' => 'required',
            'closing' => 'required',
        ]);

        Shift::create($request->all());
        return response()->json(['message' => 'Shif created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        $route = route('shifts.update', $shift->id);
        $formMethod = 'PUT';
        return view('shifts.form', compact('shift', 'route', 'formMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'opening' => 'required',
            'closing' => 'required',
        ]);

        $shift->update($request->all());
        return response()->json(['message' => 'Shift updated successfully'], 200);
        }
        
        /**
         * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return response()->json(['message' => 'Shift deleted successfully'], 200);
    }
}
