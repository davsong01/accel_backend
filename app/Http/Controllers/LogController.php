<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
     /**
     * Display a listing of the Audit log.
     */
    public function audit(): JsonResponse
    {
        $logs = AdminLog::with(['createdBy:id,type,status,first_name,last_name,middle_name,title,avatar', 'causee:id,type,status,first_name,last_name,middle_name,title,avatar'])->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $logs], 200);
    }

     /**
     * Search/filter a listing of the Audit log.
     */
    public function search(Request $request): JsonResponse
    {
        $params = $request->only(['crud_type']);
        
        $query =AdminLog::with(['createdBy:id,type,status,first_name,last_name,middle_name,title,avatar', 'causee:id,type,status,first_name,last_name,middle_name,title,avatar'])->where($params);

        if($request->input('title') && $title = $request->input('title')){
            $query->where('title', 'LIKE', '%'. $title .'%');
        }

        if($request->input('ip') && $ip = $request->input('ip')){
            $query->where('ip', 'LIKE', '%'. $ip .'%');
        }

        $from = $request->input('from');
        $to = $request->input('to');

        if($from && $to){
            $query->whereBetween('created_at', [$from, $to]);
        }

        if($request->input('createdBy') && $name = $request->input('createdBy')){
            $user = User::where('first_name', 'LIKE', '%'.$name.'%')
            ->orWhere('last_name', 'LIKE', '%'.$name.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$name.'%')->first();
            $query->where('user_id', $user?->id)->where('user_id', '!=', null);
        }

        if($request->input('causee') && $name = $request->input('causee')){
            $user = User::where('first_name', 'LIKE', '%'.$name.'%')
            ->orWhere('last_name', 'LIKE', '%'.$name.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$name.'%')->first();
            $query->where('causee_id', $user?->id)->where('causee_id', '!=', null);
        }

        $logs = $query->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $logs], 200);
    }
}
