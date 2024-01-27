<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
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
}
