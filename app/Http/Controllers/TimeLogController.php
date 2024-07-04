<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
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
            $timeLog = TimeLog::with('user')->orderBy('id', 'DESC')->paginate(15);
            return view('time-logs.index', compact('timeLog'));
        } else {
            $timeLog = TimeLog::where('user_id', $user->id)->with('user')->orderBy('id', 'DESC')->paginate(15);
            return view('time-logs.index', compact('timeLog'));
        }
        // $user = auth()->user();
        // $timeLogs = [];

        // if (isAdmin($user)) {
        //     TimeLog::with('user')->chunk(10, function ($logs) use (&$timeLogs) {
        //         foreach ($logs as $log) {
        //             $timeLogs[] = $log;
        //         }
        //     });
        // } else {
        //     TimeLog::where('user_id', $user->id)->with('user')->chunk(10, function ($logs) use (&$timeLogs) {
        //         foreach ($logs as $log) {
        //             $timeLogs[] = $log;
        //         }
        //     });
        // }
        // return view('time-logs.index', compact('timeLogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function show(TimeLog $timeLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeLog $timeLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeLog $timeLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeLog $timeLog)
    {
        $timeLog->delete();
        return response()->json(['message' => 'Time Log deleted successfully'], 200);
    }

    // public function startTime(Request $request)
    // {
    //     $userId = Auth::id();
    //     $currentDate = now()->toDateString();
    
    //     $attendance = Attendance::where('user_id', $userId)
    //         ->whereDate('attendance_date', $currentDate)
    //         ->whereNotNull('check_in')
    //         ->first();
    
    //     if (!$attendance) {
    //         return response()->json(['message' => 'You have not checked in today.'], 400);
    //     }
    
    //     $timelog = Timelog::where('user_id', $userId)
    //         ->whereDate('created_at', $currentDate)
    //         ->whereNull('end_time')
    //         ->first();
    
    //     if ($timelog) {
    //         return response()->json(['message' => 'You must log the end time before starting a new log.'], 400);
    //     }
    
    //     $newTimelog = Timelog::create([
    //         'user_id' => $userId,
    //         'attendance_id' => $attendance->id,
    //         'start_time' => now()->format('H:i:s')
    //     ]);
    
    //     return response()->json([
    //         'message' => 'Start time logged successfully.',
    //         'time_log_id' => $newTimelog->id
    //     ], 200);
    // }

    // public function endTimeTracker(Request $request, $timeLogId)
    // {
    //     $userId = Auth::id();
    //     $currentDate = now()->toDateString();
    
    //     $attendance = Attendance::where('user_id', $userId)
    //         ->whereDate('attendance_date', $currentDate)
    //         ->whereNotNull('check_in')
    //         ->first();
    
    //     if (!$attendance) {
    //         return response()->json(['message' => 'You have not checked in today.'], 400);
    //     }
    
    //     $startTime = $request->session()->get('startTime');
    
    //     $timelog = Timelog::where('id', $timeLogId)
    //         ->where('user_id', $userId)
    //         ->whereDate('created_at', $currentDate)
    //         ->whereNull('end_time')
    //         ->first();
    
    //     if (!$timelog) {
    //         return response()->json(['message' => 'No start time logged for today.'], 400);
    //     }
    
    //     $startTimeTimestamp = $startTime;
    //     $endTimeTimestamp = now()->timestamp;
    //     $durationInSeconds = $endTimeTimestamp - $startTimeTimestamp;
    //     $duration = gmdate('H:i:s', $durationInSeconds);
    
    //     $timelog->update([
    //         'end_time' => now()->format('H:i:s'),
    //         'duration' => $duration
    //     ]);
    
    //     $request->session()->forget(['startTime', 'timeLogId']);
    
    //     return response()->json(['message' => 'End time logged successfully.'], 200);
    // }

    // public function startTime(Request $request)
    // {
    //     $userId = Auth::id();
    //     $currentDate = now()->toDateString();
    //     $attendance = Attendance::where('user_id', $userId)
    //         ->whereDate('attendance_date', $currentDate)
    //         ->whereNotNull('check_in')
    //         ->first();
    //     if (!$attendance) {
    //         return response()->json(['message' => 'You have not checked in today.'], 400);
    //     }
    //     $timelog = Timelog::where('user_id', $userId)
    //         ->whereDate('created_at', $currentDate)
    //         ->whereNull('end_time')
    //         ->first();
    //     if ($timelog) {
    //         return response()->json(['message' => 'You must log the end time before starting a new log.'], 400);
    //     }
    //     $newTimelog = Timelog::create([
    //         'user_id' => $userId,
    //         'attendance_id' => $attendance->id,
    //         'start_time' => now()->format('H:i:s')
    //     ]);
    //     return response()->json([
    //         'message' => 'Start time logged successfully.',
    //         'time_log_id' => $newTimelog->id
    //     ], 200);
    // }
    
    // public function endTimeTracker(Request $request, TimeLog $timeLog)
    // {
    //     $userId = Auth::id();
    //     $currentDate = now()->toDateString();
    //     $attendance = Attendance::where('user_id', $userId)
    //         ->whereDate('attendance_date', $currentDate)
    //         ->whereNotNull('check_in')
    //         ->first();
    //     if (!$attendance) {
    //         return response()->json(['message' => 'You have not checked in today.'], 400);
    //     }
    //     $timelog = Timelog::where('id', $timeLog->id)
    //         ->where('user_id', $userId)
    //         ->whereDate('created_at', $currentDate)
    //         ->whereNull('end_time')
    //         ->first();
    //     if (!$timelog) {
    //         return response()->json(['message' => 'No start time logged for today.'], 400);
    //     }
    //     $startTime = strtotime($timelog->start_time);
    //     $endTime = strtotime(now()->format('H:i:s'));
    //     $durationInSeconds = $endTime - $startTime;
    //     $duration = gmdate('H:i:s', $durationInSeconds);
    //     $timelog->update([
    //         'end_time' => now()->format('H:i:s'),
    //         'duration' => $duration
    //     ]);
    //     return response()->json(['message' => 'End time logged successfully.'], 200);
    // }
    
}
