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
        return response()->json(['message' => 'Success', 'data' => $types], 200);
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

        return response()->json(['message' => 'Success', 'data' => $document], 200);
    }

    /**
     * preview a document
     */
    public function preview(Request $request): JsonResponse
    {
        $params = $request->input();
        $types = LegalDocumentTemplate::where($params)->first();
        return response()->json(['message' => 'Success', 'data' => $types], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function estateContracts(Request $request): JsonResponse
    {
        $params = $request->only((new LegalDocumentTemplate)->getFillable());
        $legalDocumentTemplate = LegalDocumentTemplate::where($params)->firstOrFail();
        
        $data = null;
        if($legalDocumentTemplate->legalDocumentType->name === 'OFFER_LETTER'){
            $data =  CustomerContract::with(['customer.user:first_name,last_name,id,middle_name,customerId,avatar', 'generatedDocument', 'location'])->where([
                'estate_id' => $request->input('estate_id'),
                'form_approval_status' => 'approved',
            ])->paginate(20);
        }
        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    /**
     * Generate legal document
     */
    public function generate(Request $request): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');
        $estate_id = $this->sessionDetails('estate_id');

        $validator = Validator::make($request->all(), [
            'contract_id' => ['required', 'exists:customer_contracts,id,company_id,' . $company_id],
            'legal_document_type_id' => ['required', 'exists:legal_document_types,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $legalDocumentTemplate = LegalDocumentTemplate::where([
            'legal_document_type_id' => $request->legal_document_type_id,
            'estate_id' => $estate_id,
            'company_id' => $this->sessionDetails('company_id'),
            ])->firstOrFail();

        $documentGen = $this->documentGenerator($request->contract_id, $legalDocumentTemplate);

        $generatedLegalDocument = GeneratedLegalDocument::create([
            'contract_id' => $request->contract_id,
            'legal_document_type_id' => $request->legal_document_type_id,
            'file_location' => $documentGen['filepath'] . $documentGen['filename'],
            'estate_id' => $legalDocumentTemplate->estate_id,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Success', 'data' => $generatedLegalDocument], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function regenerate(Request $request, GeneratedLegalDocument $document): JsonResponse
    {
        $documentGen = $this->documentGenerator($document->contract_id, $document->template);
        $document->update([
            'regenerate_count' => $document->regenerate_count + 1,
            'file_location' => $documentGen['filepath'] . $documentGen['filename']
        ]);

        return response()->json(['message' => 'Success', 'data' => $document], 200);
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

    private function getOptions($options, $contract_id, $legalDocumentTemplate)
    {
        $customer = CustomerContract::find($contract_id)->customer;
        $values = [];

        $values['customer_name'] = key_exists('customer_name', $options) ? $customer->user->first_name . ' ' . $customer->user->last_name : null;
        $values['date'] = key_exists('date', $options) ? date("M jS, Y", strtotime(now())) : null;
        $values['customer_phone'] = key_exists('customer_phone', $options) ? $customer->user->phone_number : null;
        $values['company_name'] = key_exists('company_name', $options) ? $this->sessionDetails('company_name') : null;

        return $values;
    }


    private function documentGenerator($contract_id, $legalDocumentTemplate)
    {
        $file = getEncodedFileRealPath($legalDocumentTemplate->file_location);

        $options = $this->setOptions();
        $values = $this->getOptions($options, $contract_id, $legalDocumentTemplate);

        $filepath = config('app.upload_path') . '/' . $this->getPathToSaveFile('generated-legal-documents') . '/';
        $filename =  uniqid(11) . '.pdf';
        $destination = base_path($filepath . '/' . $filename);

        GeneratedDocumentService::handler($file['filepath'], $values, $destination);

        return [
            'filepath' => $filepath,
            'filename' => $filename,
        ];
    }
}
