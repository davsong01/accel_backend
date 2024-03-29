<?php

namespace App\Services;

use App\Models\AdminLog;
use GuzzleHttp\Psr7\Request;

class LogService
{
    
    /**
     * Logs to database for admins
     * 
     * @param int $user_id Authenticated user id
     * @param string $title Title of action being carried out. 
     * @param string $crud_type Action type in UPDATE, CREATE or DELETE 
     * @param int $company_id auth user company id 
     */
    static function admin(int $user_id, int | null $cuasee_id, string $title, string $crud_type, int | null $company_id = null)
    {
        AdminLog::create([
            'created_by' => $user_id,
            'company_id' => request()->input('company_id') ?? $company_id,
            'causee_id' => $cuasee_id,
            'title' => $title,
            'ip' => request()->ip(),
            'crud_type' => $crud_type
        ]);
    }
}
