<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GeneratedLegalDocument;
use App\Models\LegalDocumentTemplate;
use App\Services\GeneratedDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GeneratedLegalDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $legalDocuments = GeneratedLegalDocument::with(['customer', 'estate'])
            ->orderBy('created_at', 'desc')
            ->where([
                'company_id' => $this->sessionDetails('company_id'),
            ])
            ->paginate(20);

        return response()->json(['message' => 'Success', 'data' => $legalDocuments], 200);
    }


    /**
     * Search the listing the resource.
     */
    public function search(Request $request): JsonResponse
    {
        $params = $request->input();
        $documents = GeneratedLegalDocument::with(['customer', 'estate'])
            ->orderBy('created_at', 'desc')
            ->where($params)
            ->where([
                'company_id' => $this->sessionDetails('company_id'),
            ])
            ->paginate(20);

        return response()->json(['message' => 'Success', 'data' => $documents], 200);
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

        return response()->json(['message' => 'Success', 'data' => $generatedLegalDocument], 200);
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

        return response()->json(['message' => 'Success', 'data' => $generatedLegalDocument], 200);
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
        $file = getEncodedFileRealPath($legalDocumentTemplate->file_location);

        $options = $this->setOptions();
        $values = $this->getOptions($options, $customer_id, $legalDocumentTemplate);

        $filepath = $this->getPathToSaveFile('generated-legal-documents');
        $filename =  uniqid(11) . '.pdf';
        $destination = base_path($filepath . '/' . $filename);
        
        GeneratedDocumentService::handler($file['filepath'], $values, $destination);

        return [
            'filepath' => str_replace('generated-legal-documents/', '', $filepath) . '/',
            'filename' => $filename,
        ];
    }
}
