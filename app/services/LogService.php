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
     */
    static function admin(int $user_id, int $cuasee_id, string $title, string $crud_type)
    {
        AdminLog::create([
            'created_by' => $user_id,
            'causee_id' => $cuasee_id,
            'title' => $title,
            'ip' => request()->ip(),
            'crud_type' => $crud_type
        ]);
    }
}
