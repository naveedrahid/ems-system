<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::with('department')->get();
        return view('notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = Department::pluck('department_name', 'id');
        $notice = new Notice();
        $route = route('notices.store');
        $formMethod = 'POST';

        return view('notices.form', compact('notice', 'route', 'formMethod', 'department'));
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
            'name' => 'required',
            'notice_type' => 'required|in:announcement,celebration',
            'department_id' => 'required',
            'description' => 'required',
            'status' => 'required|in:' . implode(',', array_keys(Notice::getStatusOptions())),
        ]);

        Notice::create($request->all());
        return response()->json(['message' => 'Notice Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        return response()->json([
            'title' =>  $notice->name,
            'notice_type' => $notice->notice_type,
            'notice_date' => \Carbon\Carbon::parse($notice->created_at)->format('d M Y'),
            'notice_time' => \Carbon\Carbon::parse($notice->created_at)->format('g:i a'),
            'description' => $notice->description,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        $department = Department::pluck('department_name', 'id');
        $route = route('notices.update', $notice->id);
        $formMethod = 'PUT';
        return view('notices.form', compact('notice', 'route', 'formMethod', 'department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'name' => 'required',
            'notice_type' => 'required|in:announcement,celebration',
            'department_id' => 'required',
            'description' => 'required',
            'status' => 'required|in:' . implode(',', array_keys(Notice::getStatusOptions())),
        ]);

        $notice->update($request->all());
        return response()->json(['message' => 'Notice updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return response()->json(['message' => 'Notice Deleted successfully'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        $notice->status = $request->status;
        $notice->save();

        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
