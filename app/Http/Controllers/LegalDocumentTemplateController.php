<?php

namespace App\Http\Controllers;

use App\Models\LegalDocumentTemplate;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LegalDocumentTemplateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'estate_id' => ['required', 'exists:estates,id'],
            // 'company_id' => ['required', 'exists:companies,id'],
            'file' => ['required', 'mimes:docx,doc'],
            'legal_document_type_id' => ['required', 'exists:legal_document_types,id'],
            'property_type_id' => ['required', 'exists:property_types,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = [
            'property_type_id' => $request->property_type_id,
            'legal_document_type_id' => $request->legal_document_type_id,
            'estate_id' => $request->estate_id,
            'company_id' => sessionDetails('company_id'), // $request->company_id,
            'created_by' => 1,
            'file_location' =>  $this->uploadProtectedFile($request->file, sessionDetails('company_slug'))['filepath']
        ];

        $document = LegalDocumentTemplate::create($data);

        LogService::admin(auth()->user()->id, null, 'Create a legal document type (' . $document->id  . ')' , 'CREATE');
        return response()->json(['message' => 'Success', 'data' => $document], 200);
    }
}
