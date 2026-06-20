<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function logActivity(string $action, string $description, $subject = null): void
    {
        ActivityLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'description'    => $description,
            'subject_type'   => $subject ? get_class($subject) : null,
            'subject_id'     => $subject?->id,
            'ip_address'     => request()->ip(),
        ]);
    }
}
