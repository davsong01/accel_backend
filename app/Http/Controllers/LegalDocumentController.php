<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\GeneratedLegalDocument;
use App\Models\LegalDocumentTemplate;
use App\Models\LegalDocumentType;
use App\Models\User;
use App\Services\GeneratedDocumentService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LegalDocumentController extends Controller
{

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');

        $documents = GeneratedLegalDocument::where('company_id', $company_id)->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $documents], 200);
    }

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
    public function template(Request $request)
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

        $filepath = $this->uploadProtectedFile($request->file, 'uploaded_legal_document_templates')['filepath'];
        $data = [
            ...$validator->validated(),
            'company_id' => $this->sessionDetails('company_id'),
            'created_by' => Auth::user()->id,
            'file_location' =>  $filepath
        ];

        $document = LegalDocumentTemplate::updateOrcreate([
            'estate_id' => $data['estate_id'],
            'company_id' => $this->sessionDetails('company_id'),
            'legal_document_type_id' => $data['legal_document_type_id']
        ], $data);

        LogService::admin(auth()->user()->id, null, 'Update or create a legal document type (' . $document->id  . ')' , 'CREATE');
        return response()->download(base_path($filepath));
    }

    /**
     * preview a document
     */
    public function preview(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');
        $params = $request->input((new LegalDocumentTemplate)->getFillable());
        $template = LegalDocumentTemplate::where('company_id', $company_id)->where($request->all())->first();
        
        if(!$template){
            return response()->json(['message' => 'No Legal document template found for sent request parameters at: ' . $this->sessionDetails('company_name'), 'data' => []], 200);
        }
        $is_sample = request()->routeIs('template.preview.sample');
        
        if($is_sample){
            $message = 'No '. $template->legalDocumentType->type . ' ' .  $template->legalDocumentType->name.' sample found for '. $this->sessionDetails('company_name');
            $downloadPath = !empty($template->template_sample) ? base_path() . '/' . $template->template_sample : null;
        }else{
            $message = 'No ' . $template->legalDocumentType->type . ' ' .  $template->legalDocumentType->name . ' uploaded for sent request parameters at: ' . $this->sessionDetails('company_name');
            $downloadPath = !empty($template->file_location) ? base_path() . '/' . $template->file_location : null;
        }
      
        if(!$downloadPath){
            return response()->json(['message' => $message, 'data' => []], 200);
        }

        return response()->download($downloadPath);

    }

    /**
     * Display a listing of the resource.
     */
    public function estateContracts(Request $request): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');
        
        $params = $request->only((new LegalDocumentTemplate)->getFillable());
        $legalDocumentTemplate = LegalDocumentTemplate::where('company_id', $company_id)->where($params)->firstOrFail();

        $data = null;
        if($legalDocumentTemplate->legalDocumentType->name === 'OFFER_LETTER'){
            $data =  CustomerContract::with(['customer.user:first_name,last_name,id,middle_name,customerId,avatar', 'generatedDocument', 'location'])->where([
                ...($request->input('estate_id') ? ['estate_id' => $request->input('estate_id')]: []),
                'form_approval_status' => 'approved',
            ])->paginate(20);
        }
        // TODO: condition for other legal document types
        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    /**
     * Generate legal document
     */
    public function generate(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');
        
        $validator = Validator::make($request->all(), [
            'contract_id' => ['required', 'exists:customer_contracts,id,company_id,' . $company_id],
            'legal_document_type_id' => ['required', 'exists:legal_document_types,id'],
            'estate_id' => ['required', 'exists:estates,id,company_id,' . $company_id],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $legalDocumentTemplate = LegalDocumentTemplate::where([
            'legal_document_type_id' => $request->legal_document_type_id,
            'estate_id' => $request->estate_id,
            'company_id' => $company_id,
            ])->firstOrFail();
        
        $documentGen = $this->documentGenerator($request->contract_id, $legalDocumentTemplate);
        $filepath = $documentGen['filepath'] . $documentGen['filename'];
        $generatedLegalDocument = GeneratedLegalDocument::updateOrCreate([
            'contract_id' => $request->contract_id,
            'legal_document_template_id' => $legalDocumentTemplate->id,
            'estate_id' => $request->estate_id,
            'company_id' => $this->sessionDetails('company_id'),
        ],
        [
            'file_location' => $filepath,
        ]);

        // update regenerate_count
        if($generatedLegalDocument->created_at->diffForHumans() !== $generatedLegalDocument->updated_at->diffForHumans()){
            $generatedLegalDocument->regenerate_count++;
            $generatedLegalDocument->save();
        }

        LogService::admin(auth()->user()->id, null, 'Update a generated legal document (' . $generatedLegalDocument->id  . ')' , 'UPDATE');
        return response()->download(base_path($filepath));
    }

    /**
     * Update the specified resource in storage.
     */
    public function regenerate(Request $request, GeneratedLegalDocument $document)
    {
        $documentGen = $this->documentGenerator($document->contract_id, $document->template);
        $filepath =  $documentGen['filepath'] . $documentGen['filename'];

        $document->update([
            'regenerate_count' => $document->regenerate_count + 1,
            'file_location' =>$filepath
        ]);

        return response()->download(base_path($filepath));
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
        $file = base_path().'/'.$legalDocumentTemplate->file_location ?? $legalDocumentTemplate->template_sample;   
        $options = $this->setOptions();
        $values = $this->getOptions($options, $contract_id, $legalDocumentTemplate);

        $filepath = config('app.upload_path') . '/' . $this->getPathToSaveFile('generated-legal-documents') . '/';
        $filename =  uniqid(11) . '.pdf';
        $destination = base_path($filepath . '/' . $filename);
        
        GeneratedDocumentService::handler($file, $values, $destination);

        return [
            'filepath' => $filepath,
            'filename' => $filename,
        ];
    }
}
