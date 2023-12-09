<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GeneratedLegalDocument;
use App\Models\LegalDocumentTemplate;
use App\Models\LegalDocumentType;
use App\Models\User;
use App\Services\GeneratedDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LegalDocumentController extends Controller
{
    /**
     * 
     */
    public function type(Request $request, string $type){
        $types = LegalDocumentType::where('type', strtoupper($type))->paginate(20);
        return response()->json(['message' => 'success', 'data' => $types], 200);
    }

      /**
     * Store a newly created resource in storage.
     */
    public function template(Request $request): JsonResponse
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
            'company_id' => $this->sessionDetails('company_id'), // $request->company_id,
            'created_by' => Auth::user()->id,
            'file_location' =>  $this->uploadProtectedFile($request->file, $this->sessionDetails('company_slug'))['filepath']
        ];

        $document = LegalDocumentTemplate::create($data);
        return response()->json(['message' => 'success', 'data' => $document], 200);
    }

      /**
     * Display a listing of the resource.
     */
    public function generated(): JsonResponse
    {
        $legalDocuments = GeneratedLegalDocument::with(['customer', 'estate'])
            ->orderBy('created_at', 'desc')
            ->where([
                'company_id' => $this->sessionDetails('company_id'),
            ])
            ->paginate(20);

        return response()->json(['message' => 'success', 'data' => $legalDocuments], 200);
    }

        /**
     * Generate legal document
     */
    public function generate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', 'exists:customers,id'],
            'document_template_id' => ['required', 'exists:legal_document_templates,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        $legalDocumentTemplate = LegalDocumentTemplate::find($request->document_template_id);

        $documentGen = $this->documentGenerator($request->customer_id, $legalDocumentTemplate);

        $generatedLegalDocument = GeneratedLegalDocument::create([
            'customer_id' => $request->customer_id,
            'document_template_id' => $request->document_template_id,
            'file_location' => $documentGen['filepath'] . $documentGen['filename'],
            'estate_id' => $legalDocumentTemplate->estate_id,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'success', 'data' => $generatedLegalDocument], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function regenerate(Request $request, GeneratedLegalDocument $generatedLegalDocument): JsonResponse
    {
        $validator = Validator::make([
            'document' => $generatedLegalDocument
        ], [
            'document' => ['required', 'exists:generated_legal_documents,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $generatedLegalDocument = GeneratedLegalDocument::find($generatedLegalDocument);

        $documentGen = $this->documentGenerator($request->customer_id, $generatedLegalDocument->documentTemplate);
        $generatedLegalDocument->update([
            'regenerate_count' => $generatedLegalDocument->regenerate_count + 1,
            'file_location' => $documentGen['filepath'] . $documentGen['filename']
        ]);

        return response()->json(['message' => 'success', 'data' => $generatedLegalDocument], 200);
    }

    private function setOptions()
    {
        $options = [
            'customer_name' => null,
            'date' => null,
            'customer_phone' => null,
            'company_name' => null,
        ];

        return $options;
    }

    private function getOptions($options, $customer_id, $legalDocumentTemplate)
    {
        $customer = Customer::where('id', $customer_id)->first();
        $values = [];

        $values['customer_name'] = key_exists('customer_name', $options) ? $customer->user->first_name . ' ' . $customer->user->last_name : null;
        $values['date'] = key_exists('date', $options) ? date("M jS, Y", strtotime(now())) : null;
        $values['customer_phone'] = key_exists('customer_phone', $options) ? $customer->user->phone_number : null;
        $values['company_name'] = key_exists('company_name', $options) ? $this->sessionDetails('company_name') : null;

        return $values;
    }


    private function documentGenerator($customer_id, $legalDocumentTemplate)
    {
        $file = reConstructUploadedProtectedDocument($legalDocumentTemplate->file_location);

        $options = $this->setOptions();
        $values = $this->getOptions($options, $customer_id, $legalDocumentTemplate);

        $filepath = $this->getPathToSaveFile('generated-legal-documents/' . $this->sessionDetails('company_slug'));
        $filename =  uniqid(11) . '.pdf';
        $destination = base_path($filepath . '/' . $filename);

        GeneratedDocumentService::handler(base_path($file['filepath']), $values, $destination);

        return [
            'filepath' =>str_replace('generated-legal-documents/', '', $filepath) . '/',
            'filename' => $filename,
        ];
    }
}
