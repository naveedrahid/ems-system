<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        if ($user->role_id === 3) {
            $leaveApplications = LeaveApplication::where('employee_id', $user->id)
                ->with(['leaveType', 'user'])
                ->paginate(10);
        } else {
            $leaveApplications = LeaveApplication::with(['leaveType', 'user'])
                ->paginate(10);
        }
        return view('leave-application.index', compact('leaveApplications', 'userId', 'roleId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leave-application.create', compact('leaveTypes'));
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
            'leave_type_id' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'reason' => 'required',
        ]);

        $imageName = null;
        if ($request->hasFile('leave_image') && $request->file('leave_image')->isValid()) {
            $imageName = time() . '.' . $request->file('leave_image')->extension();
            $request->file('leave_image')->move(public_path('upload'), $imageName);
        }

        $start_date_parsed = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $end_date_parsed = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $total_days = $start_date_parsed->diffInDays($end_date_parsed) + 1;

        $period = CarbonPeriod::create($start_date_parsed, $end_date_parsed);
        $total_days = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $total_days++;
            }
        }

        LeaveApplication::create([
            'employee_id' => Auth::id(),
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $start_date_parsed->format('Y-m-d'),
            'end_date' => $end_date_parsed->format('Y-m-d'),
            'reason' => $request->reason,
            'leave_image' => $imageName,
            'total_leave' => $total_days,
            'status' => 'Pending',
        ]);
        return response()->json(['message' => 'Leave application created successfully']);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveApplication $leaveApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveTypes = LeaveType::all();
        $statusOptions = LeaveApplication::getStatusOptions();
        return view('leave-application.edit', compact('leaveApplication', 'leaveTypes', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'nullable',
            'leave_type_id' => 'required',
            'reason' => 'required',
            'status' => 'required|in:' . implode(',', LeaveApplication::getStatusOptions()),
            'start_date' => 'sometimes|nullable|date_format:Y-m-d',
            'end_date' => 'sometimes|nullable|date_format:Y-m-d',
        ]);

        $leaveApplication = LeaveApplication::find($id);
        if (!$leaveApplication) {
            return redirect()->back()->withErrors(['error' => 'Leave Application not found.']);
        }

        $imageName = $leaveApplication->leave_image;
        if ($request->hasFile('leave_image')) {
            if ($leaveApplication->leave_image) {
                $imagePath = public_path('upload') . '/' . $leaveApplication->leave_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageName = time() . '.' . $request->file('leave_image')->getClientOriginalExtension();
            $request->file('leave_image')->move(public_path('upload'), $imageName);
        }

        $start_date = $leaveApplication->start_date;
        $end_date = $leaveApplication->end_date;
        $total_days = $leaveApplication->total_leave;

        if ($request->start_date && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $start_date_parsed = Carbon::createFromFormat('Y-m-d', $start_date);
            $end_date_parsed = Carbon::createFromFormat('Y-m-d', $end_date);
            $period = CarbonPeriod::create($start_date_parsed, $end_date_parsed);
            $total_days = 0;
            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $total_days++;
                }
            }
        }

        $data = [
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reason' => $request->reason,
            'leave_image' => $imageName,
            'status' => $request->status,
            'total_leave' => $total_days,
        ];

        $leaveApplication->update($data);

        return response()->json(['message' => 'Leave application Updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);

        if ($leaveApplication->leave_image) {
            $imagePath = public_path('upload') . '/' . $leaveApplication->leave_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $leaveApplication->delete();

        return response()->json(['success' => 'Leave Application deleted successfully'], 200);
    }
}
