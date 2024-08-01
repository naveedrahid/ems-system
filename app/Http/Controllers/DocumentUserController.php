<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DocumentUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $documentUsers = DocumentUser::with('user')->paginate(10);

        return view('documents.index', compact('documentUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fromEmployee = $request->input('document_user');
        $departments = Department::with('employees.user')->get();
        $userName = null;
        $employeeDepartment = null;
        if ($fromEmployee) {
            $userName = User::find($fromEmployee)->name;
            $dpId = $departments->flatMap(function ($department) {
                return $department->employees;
            })->firstWhere('user_id', $fromEmployee);

            $employeeDepartment = $departments->find($dpId->department_id);
        }
        $document = new DocumentUser();
        $route = route('documents.store');
        $formMethod = 'POST';
        $users = User::all();

        return view('documents.form', compact('document', 'route', 'formMethod', 'departments', 'users', 'fromEmployee', 'userName', 'employeeDepartment'));
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
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'nic_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nic_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resume' => 'required|file|mimes:pdf,doc,docx',
            'payslip' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'experience_letter' => 'required|file|mimes:pdf,doc,docx',
            'bill' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $document = new DocumentUser();
        $document->user_id = $request->user_id;
        $document->department_id = $request->department_id;
        $userName = User::where('id', $document->user_id)->value('name');
        $sanitizedUserName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $userName);

        $files = ['nic_front', 'nic_back', 'resume', 'payslip', 'experience_letter', 'bill'];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                $filePath = $uploadedFile->storeAs('user_docs/' . $sanitizedUserName, $fileName, 'public');
                $document->$file = 'storage/' . $filePath;
            }
        }

        $document->save();

        return response()->json(['message' => 'Document saved successfully!', 'data' => $document]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentUser $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentUser $document)
    {
        $route = route('documents.update', $document->id);
        $formMethod = 'PUT';
        $departments = Department::with('employees.user')->get();
        $users = User::all();
        return view('documents.form', compact('users', 'document', 'route', 'formMethod', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, DocumentUser $document)
    public function update(Request $request, $id)
    {
        $validationRules = [
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'nic_front' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nic_back' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resume' => 'nullable|file|mimes:pdf,doc,docx',
            'payslip' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'experience_letter' => 'nullable|file|mimes:pdf,doc,docx',
            'bill' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $request->validate($validationRules);

        $document = DocumentUser::findOrFail($id);
        $document->user_id = $request->user_id;
        $document->department_id = $request->department_id;
        $userName = User::where('id', $document->user_id)->value('name');
        $sanitizedUserName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $userName);

        $files = ['nic_front', 'nic_back', 'resume', 'payslip', 'experience_letter', 'bill'];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                if ($document->$file) {
                    $oldFilePath = str_replace('storage/', '', $document->$file);
                    Storage::disk('public')->delete($oldFilePath);
                }

                $uploadedFile = $request->file($file);
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                $filePath = $uploadedFile->storeAs('user_docs/' . $sanitizedUserName, $fileName, 'public');
                $document->$file = 'storage/' . $filePath;
            }
        }

        $document->save();

        return response()->json(['message' => 'Document updated successfully!', 'data' => $document]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentUser  $documentUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentUser $document)
    {
        $user = User::find($document->user_id);

        if ($user) {
            $sanitizedUserName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $user->name);

            $userFolder = 'user_docs/' . $sanitizedUserName;

            if (Storage::disk('public')->exists($userFolder)) {
                Storage::disk('public')->deleteDirectory($userFolder);
            }
        }

        $document->delete();

        return response()->json(['message' => 'Documents deleted successfully.']);
    }


    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $path = $request->file('file')->store('user_docs');

        return response()->json(['path' => $path], 200);
    }
}
