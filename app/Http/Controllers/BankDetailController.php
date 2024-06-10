<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankDetails = BankDetail::all();
        $employees = User::whereIn('id', $bankDetails->pluck('user_id'))->get()->pluck('name', 'id');
        // dd($employees);
        return view('bank-details.index', compact('bankDetails', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, BankDetail $bankDetail)
    {
        $employeeId = $request->query('employee_id');
        $employeeName = null;
        $userId = null;
    
        if ($employeeId) {
            $employee = Employee::with('user')->find($employeeId);
            if ($employee) {
                $employeeName = optional($employee->user)->name;
                $userId = optional($employee->user)->id;
            }
        }
    
        $employees = Employee::with('user')->get(['id', 'user_id']);
        $employees = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'user_id' => optional($employee->user)->id,
                'name' => optional($employee->user)->name,
            ];
        });
    
        $route = route('bank-details.store');
        $formMethod = 'POST';
    
        return view('bank-details.form', compact('bankDetail', 'employees', 'route', 'formMethod', 'employeeName', 'employeeId', 'userId'));
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
            'user_id' => 'required',
            'employee_id' => 'required',
            'bank_name' => 'required',
            'account_title' => 'required',
            'account_number' => 'required|numeric',
            'ibn' => 'required',
            'branch_code' => 'required',
            'branch_address' => 'required',
            'status' => 'required|in:active,deactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        BankDetail::create($validatedData);

        return response()->json(['message' => 'Bank Detail created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function show(BankDetail $bankDetail)
    {
        //    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BankDetail $bankDetail)
    {
        $employees = Employee::with('user')->get(['id', 'user_id']);
        $employees = $employees->map(function ($employee) {
            $user_id = optional($employee->user)->id;
            $name = optional($employee->user)->name;
            return [
                'id' => $employee->id,
                'user_id' => $user_id,
                'name' => $name
            ];
        });

        $userId = $bankDetail->user_id;
        $employeeId = $bankDetail->employee_id;
        $employeeName = optional($bankDetail->employee)->name;

        $route = route('bank-details.update', $bankDetail->id);
        $formMethod = 'PUT';

        return view('bank-details.form', compact('bankDetail', 'employees', 'route', 'formMethod', 'userId', 'employeeId', 'employeeName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankDetail  $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankDetail $bankDetail)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'employee_id' => 'required',
            'bank_name' => 'required',
            'account_title' => 'required',
            'account_number' => 'required|numeric',
            'ibn' => 'required',
            'branch_code' => 'required',
            'branch_address' => 'required',
            'status' => 'required|in:active,deactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bankDetail->update($request->all());

        return response()->json(['message' => 'Bank Detail updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankDetail $bankDetail)
    {
        $bankDetail->delete();
        return response()->json(['success' => 'Bank Details deleted successfully'], 200);
    }

    public function bankStatus(Request $request, BankDetail $bankDetail)
    {
        $bankDetail->status = $request->status;
        $bankDetail->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
