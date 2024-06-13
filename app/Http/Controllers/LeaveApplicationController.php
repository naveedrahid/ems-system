<?php

namespace App\Http\Controllers;

use App\Mail\LeaveApplicationMail;
use App\Mail\LeaveApprovalStatusMail;
use App\Models\LeaveType;
use App\Models\LeaveApplication;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $roleId = $user->role_id;

        if ($roleId === 1 || $roleId === 2) {
            $leaveApplications = LeaveApplication::with(['leaveType', 'user'])->paginate(10);
        } else {
            $leaveApplications = LeaveApplication::where('user_id', $user->id)->paginate(10);
        }

        return view('leave-application.index', compact('leaveApplications', 'roleId'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leave_application = new LeaveApplication(); //Remove the parameter from here
        $route = route('leave-applications.store'); // Change the route to the store method
        $formMethod = 'POST';
        $leaveTypes = LeaveType::pluck('name', 'id')->toArray();
        $users = User::pluck('name', 'id')->toArray();
        return view('leave-application.form', compact('leave_application', 'route', 'formMethod', 'leaveTypes', 'users'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
                $request->validate([
            'employee_id' => 'nullable',
            'user_id' => 'required',
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

        $period = CarbonPeriod::create($start_date_parsed, $end_date_parsed);
        $total_days = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $total_days++;
            }
        }

        $user = Auth::user();
        // if (isAdmin($user)) {
        //     $employee_id = $request->selected_user_id;
        // } else {
        //     $employee_id = $request->user_id;
        // }        

        LeaveApplication::create([
            'employee_id' => 1,
            'user_id' => $request->user_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $start_date_parsed->format('Y-m-d'),
            'end_date' => $end_date_parsed->format('Y-m-d'),
            'reason' => $request->reason,
            'leave_image' => $imageName,
            'total_leave' => $total_days,
            'status' => 'Pending',
        ]);

        // $user = auth()->user();
        // Mail::to($user->email)->send(new LeaveApplicationMail($user, $LeaveApplication));

        return response()->json(['message' => 'Leave application created successfully']);
    }

    /** ~
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leave_application
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveApplication $leave_application)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leave_application
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LeaveApplication $leave_application)
    {
        $route = route('leave-applications.update', $leave_application->id);
        $formMethod = 'PUT';
        $leaveTypes = LeaveType::pluck('name', 'id')->toArray();
        $statusOptions = LeaveApplication::getStatusOptions();
        $users = User::pluck('name', 'id')->toArray();
        return view('leave-application.form', compact('leave_application', 'route', 'formMethod', 'leaveTypes', 'statusOptions', 'users'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveApplication  $leave_application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveApplication $leave_application)
    {
        $statusOptions = array_keys(LeaveApplication::getStatusOptions());
        $request->validate([
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'reason' => 'required',
            'status' => 'required|in:' . implode(',', $statusOptions),
            'start_date' => 'sometimes|nullable|date_format:Y-m-d',
            'end_date' => 'sometimes|nullable|date_format:Y-m-d',
        ]);

        if (!$leave_application) {
            return redirect()->back()->withErrors(['error' => 'Leave Application not found.']);
        }

        $imageName = $leave_application->leave_image;
        if ($request->hasFile('leave_image')) {
            if ($leave_application->leave_image) {
                $imagePath = public_path('upload') . '/' . $leave_application->leave_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageName = time() . '.' . $request->file('leave_image')->getClientOriginalExtension();
            $request->file('leave_image')->move(public_path('upload'), $imageName);
        }

        $start_date = $leave_application->start_date;
        $end_date = $leave_application->end_date;
        $total_days = $leave_application->total_leave;

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
            'user_id' => $request->user_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reason' => $request->reason,
            'leave_image' => $imageName,
            'status' => $request->status,
            'total_leave' => $total_days,
        ];

        $leave_application->update($data);

        return response()->json(['message' => 'Leave application Updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveApplication  $leave_application
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveApplication  $leave_application)
    {
        if ($leave_application->leave_image) {
            $imagePath = public_path('upload') . '/' . $leave_application->leave_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $leave_application->delete();

        return response()->json(['success' => 'Leave Application deleted successfully'], 200);
    }

    public function updateStatus(LeaveApplication $leave_application) {
        $newStatus = request()->input('status');
    
        $leave_application->status = $newStatus;
        $leave_application->save();
    
        $user = $leave_application->user;
        $status = $leave_application->status;
        Mail::to($user->email)->send(new LeaveApprovalStatusMail($user, $leave_application, $status));
    
        return response()->json(['message' => 'Status updated successfully']);
    }
    
}
