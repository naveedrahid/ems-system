<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DocumentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentUser = new DocumentUser();
        $route = route('documents.store');
        $formMethod = 'POST';
        $departments = Department::with('employees.user')->get();
    
        return view('documents.form', compact('documentUser', 'route', 'formMethod', 'departments'));
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
            'nic_front' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nic_back' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resume' => 'file|mimes:pdf,doc,docx|max:2048',
            'payslip' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'experience_letter' => 'file|mimes:pdf,doc,docx|max:2048',
            'bill' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $documentUser = new DocumentUser();
        $documentUser->user_id = $request->user_id;

        $files = ['nic_front', 'nic_back', 'resume', 'payslip', 'experience_letter', 'bill'];
    
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                $filePath = 'documents/' . $fileName;
                Storage::disk('public')->put($filePath, file_get_contents($uploadedFile));
                $documentUser->$file = 'storage/' . $filePath;
            }
        }
    
        $documentUser->save();
    
        return response()->json(['message' => 'Document saved successfully!', 'data' => $documentUser]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentUser $documentUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentUser $documentUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentUser $documentUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentUser $documentUser)
    {
        //
    }
}
