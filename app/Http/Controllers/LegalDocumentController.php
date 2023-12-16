<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContract;
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
        $params = $request->input();
        $types = LegalDocumentType::where($params)->get();
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = [
            ...$validator->validated(),
            'company_id' => $this->sessionDetails('company_id'),
            'created_by' => Auth::user()->id,
            'file_location' =>  $this->uploadProtectedFile($request->file, 'uploaded_legal_document_templates')['filepath']
        ];

        $document = LegalDocumentTemplate::updateOrcreate([
            'estate_id' => $data['estate_id'],
            'company_id' => $this->sessionDetails('company_id'),
            'legal_document_type_id' => $data['legal_document_type_id']
        ], $data);

        return response()->json(['message' => 'success', 'data' => $document], 200);
    }

    /**
     * preview a document
     */
    public function preview(Request $request): JsonResponse
    {
        $params = $request->input();
        $types = LegalDocumentTemplate::where($params)->first();
        return response()->json(['message' => 'success', 'data' => $types], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function generated(Request $request): JsonResponse
    {
        $params = $request->except('legal_document_template_id');
        $legalDocumentTemplate = LegalDocumentTemplate::where($params)->where('id', $request->input('legal_document_template_id'))->firstOrFail();

        $data = null;
        // if($legalDocumentTemplate->legalDocumentType->name === 'OFFER_LETTER'){
            $data =  CustomerContract::with(['customer.user'])->where([
                'estate_id' => $request->input('estate_id'),
                // 'status' => 'APPROVED',
                'property_type' =>  strtolower($legalDocumentTemplate->legalDocumentType->type)
            ])->paginate(20);
        // }
        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    /**
     * Generate legal document
     */
    public function generate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', 'exists:customers,id'],
            'legal_document_template_id' => ['required', 'exists:legal_document_templates,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $legalDocumentTemplate = LegalDocumentTemplate::find($request->legal_document_template_id);

        $documentGen = $this->documentGenerator($request->customer_id, $legalDocumentTemplate);

        $generatedLegalDocument = GeneratedLegalDocument::create([
            'customer_id' => $request->customer_id,
            'legal_document_template_id' => $request->legal_document_template_id,
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
