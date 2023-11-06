<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanyDocument;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CompanyDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = CompanyDocument::where('company_id', $this->sessionDetails('company_id'))->get();
        return response()->json(['message' => 'success', 'data' => $documents], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('company_documents')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'description' => 'sometimes',
            'file' => 'required',
            'visible_to_staff' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'visible_to_staff' => $request->visible_to_staff,
            'company_id' => $this->sessionDetails('company_id'),
        ];

        if ($request->has('file') && !is_null($request->file)) {
            $data['file'] = '/uploads/brands/documents/' . $this->uploadProtectedFile($request->file, 'uploads/brands/documents');
        }
        
        $document = CompanyDocument::create($data);
        return response()->json(['message' => 'success', 'data' => $document], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyDocument $companyDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyDocument $companyDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyDocument $companyDocument)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes',
            'description' => 'sometimes',
            'file' => 'sometimes',
            'visible_to_staff' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = [
            'name' => $request->name ?? null,
            'description' => $request->description,
            'visible_to_staff' => $request->visible_to_staff,
            'company_id' => $this->sessionDetails('company_id'),
        ];

        if ($request->has('file') && !is_null($request->file)) {
            $data['file'] = '/uploads/brands/documents/' . $this->uploadProtectedFile($request->file, 'uploads/brands/documents');
        }

        $companyDocument->update($data);

        return response()->json(['message' => 'success', 'data' => null], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyDocument $companyDocument)
    {
        $this->deleteImage($companyDocument->file);
        $companyDocument->delete();
        return response()->json(['message' => 'success', 'data' => null], 200);
    }
}
