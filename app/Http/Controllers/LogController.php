<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
     /**
     * Display a listing of the Audit log.
     */
    public function audit(): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');

        $logs = AdminLog::with(['createdBy:id,type,status,first_name,last_name,middle_name,title,avatar', 'causee:id,type,status,first_name,last_name,middle_name,title,avatar'])->where('company_id', $company_id)->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $logs], 200);
    }

    /**
     * Search/filter a listing of the Audit log.
     */
    public function search(Request $request): JsonResponse
    {
        $params = $request->only(['crud_type']);
        $company_id = $this->sessionDetails('company_id');

        $query = AdminLog::with(['createdBy:id,type,status,first_name,last_name,middle_name,title,avatar', 'causee:id,type,status,first_name,last_name,middle_name,title,avatar'])->where('company_id', $company_id)->where($params);

        if($request->input('title') && $title = $request->input('title')){
            $query->where('title', 'LIKE', '%'. $title .'%');
        }

        if($request->input('ip') && $ip = $request->input('ip')){
            $query->where('ip', 'LIKE', '%'. $ip .'%');
        }

        $from = $request->input('from');
        $to = $request->input('to');

        if($from && $to){
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        if($request->input('createdBy') && $name = $request->input('createdBy')){
            $users = User::where('company_id', $company_id)->whereNotNull('company_id')
            ->where('first_name', 'LIKE', '%' . $name . '%')
            ->orWhere('last_name', 'LIKE', '%' . $name . '%')
            ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();
            $created_by_ids = $users->whereNotNull('id')->pluck('id')->toArray();

            $query->whereIn('user_id',  $created_by_ids);
        }

        if($request->input('causee') && $name = $request->input('causee')){
            $users = User::where('company_id', $company_id)->whereNotNull('company_id')
            ->where('first_name', 'LIKE', '%' . $name . '%')
            ->orWhere('last_name', 'LIKE', '%' . $name . '%')
            ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();
            $causees_ids = $users->whereNotNull('id')->pluck('id')->toArray();

            $query->whereIn('causee_id', $causees_ids);
        }

        $logs = $query->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $logs], 200);
    }

    /**
     * Search/filter a listing of the Audit log based on the auth user.
     */
    public function operations(Request $request): JsonResponse
    {
        $params = $request->only(['crud_type']);
        $company_id = $this->sessionDetails('company_id');


        $query = AdminLog::with(['createdBy:id,type,status,first_name,last_name,middle_name,title,avatar', 'causee:id,type,status,first_name,last_name,middle_name,title,avatar'])
        ->where(['company_id' => $company_id, 'created_by' => auth()->user()->id])->where($params);

        if($request->input('title') && $title = $request->input('title')){
            $query->where('title', 'LIKE', '%'. $title .'%');
        }

        if($request->input('ip') && $ip = $request->input('ip')){
            $query->where('ip', 'LIKE', '%'. $ip .'%');
        }

        $from = $request->input('from');
        $to = $request->input('to');

        if($from && $to){
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        if($request->input('causee') && $name = $request->input('causee')){
            $users = User::where('company_id', $company_id)->whereNotNull('company_id')
            ->where('first_name', 'LIKE', '%' . $name . '%')
            ->orWhere('last_name', 'LIKE', '%' . $name . '%')
            ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();
            $causees_ids = $users->whereNotNull('id')->pluck('id')->toArray();

            $query->whereIn('causee_id', $causees_ids);
        }

        $logs = $query->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $logs], 200);
    }
    
}
