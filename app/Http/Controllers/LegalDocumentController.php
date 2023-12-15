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
    public function type(Request $request)
    {
        $type = $request->input('type');
        $types = LegalDocumentType::where('type', strtoupper($type))->get();
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
            'file_location' =>  $this->uploadProtectedFile($request->file, 'uploaded_legal_document_templates/'.$this->sessionDetails('company_slug'))['filepath']
        ];
        
        $document = LegalDocumentTemplate::updateOrcreate([
            'estate_id' => $data['estate_id'], 
            'company_id' => $this->sessionDetails('company_id'), 
            'legal_document_type_id' => $data['legal_document_type_id']
        ], $data);

        return response()->json(['message' => 'success', 'data' => $document], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function generated(Request $request): JsonResponse
    {
        $legalDocumentType = LegalDocumentType::where([
            'type' => $request->input('property_type'),
            'name' => $request->input('name')
        ])->first();
        
        if ($legalDocumentType) {
            $legalDocumentTemplate = LegalDocumentTemplate::where('legal_document_type_id', $legalDocumentType->id)->first();
            $data = [
                'template' => $legalDocumentTemplate,
                'customers' => Customer::with(['user', 'generatedLegalDocuments'])->paginate(20)
            ];
            return response()->json(['message' => 'success', 'data' => $data], 200);
        }

        return response()->json(['message' => 'success', 'data' => null], 200);
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
    public function regenerate(Request $request, GeneratedLegalDocument $document): JsonResponse
    {
        $documentGen = $this->documentGenerator($document->customer->id, $document->template);
        $document->update([
            'regenerate_count' => $document->regenerate_count + 1,
            'file_location' => $documentGen['filepath'] . $documentGen['filename']
        ]);

        return response()->json(['message' => 'success', 'data' => $document], 200);
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
            'filepath' => str_replace('generated-legal-documents/', '', $filepath) . '/',
            'filename' => $filename,
        ];
    }
}
