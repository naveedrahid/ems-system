<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintMail;
use App\Models\Complaint;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (isAdmin($user)) {
            $complaints = Complaint::orderBy('id', 'DESC')->get();
        } else {
            $complaints = Complaint::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }

        $userIds = $complaints->pluck('user_id');
        $employees = Employee::with(['designation', 'department', 'employeeType', 'user'])
            ->whereIn('user_id', $userIds)
            ->select('id', 'user_id', 'department_id', 'designation_id', 'employee_type_id')
            ->get();
        $statuses = Complaint::getStatuses();
        $allData = collect(['complaints' => $complaints, 'employees' => $employees, 'statuses' => $statuses]);
        return view('complaints.index', compact('allData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $complaint = new Complaint();
        $route = route('complaints.store');
        $formMethod = 'POST';
        // my static methods
        $complaintTypes = Complaint::get_complaint_types();
        // getting user details
        $user = auth()->user();
        $employee = Employee::with(['designation', 'department', 'employeeType'])
            ->where('user_id', $user->id)
            ->select('id', 'user_id', 'department_id', 'designation_id', 'employee_type_id')
            ->first();
        $employee_id = $employee->id;
        $designationName = $employee->designation->designation_name ?? null;
        $departmentName = $employee->department->department_name ?? null;
        $employeeTypeName = $employee->employeeType->type ?? null;
        $ticketNumber = 'TICKET-' . strtoupper(bin2hex(random_bytes(6)));

        return view('complaints.form', compact('complaint', 'route', 'formMethod', 'designationName', 'departmentName', 'employeeTypeName', 'ticketNumber', 'complaintTypes', 'employee_id'));
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
            'complaint_type' => 'required|in:' . implode(',', Complaint::get_complaint_types()),
            'ticket_number' => 'required|string|unique:complaints,ticket_number',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $complaint = Complaint::create([
            'user_id' => auth()->user()->id,
            'employee_id' => $request->employee_id,
            'ticket_number' => $request->ticket_number,
            'complaint_status' => 'pending',
            'complaint_type' => $request->complaint_type,
            'content' => $request->content,
        ]);

        $user = auth()->user();

        Mail::to($user->email)->send(new ComplaintMail($user,$complaint, 'create'));

        return response()->json(['message' => 'Your complaint has been send successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        $route = route('complaints.update', $complaint->id);
        $formMethod = 'PUT';
        return view('complaints.form', compact('complaint', 'route', 'formMethod'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complaint $complaint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return response()->json(['message' => 'Complaint has been deleted successfully'], 200);
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validatedData = $request->validate([
            'complaint_status' => 'required|in:' . implode(',', Complaint::getStatuses()),
        ]);
    
        $complaint->complaint_status = $validatedData['complaint_status'];
        $complaint->save();
        $complaintOwner = User::find($complaint->user_id);
        if ($complaintOwner) {
            Mail::to($complaintOwner->email)->send(new ComplaintMail($complaintOwner, $complaint, 'update'));
        }
        return response()->json(['message' => 'Status updated successfully']);
    }
}
