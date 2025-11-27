<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        abort_if(! Auth::user()?->isAdmin(), 403);

        $logs = ActivityLog::with(['causer'])
            ->latest()
            ->paginate(15);

        return view('activity.index', compact('logs'));
    }
}
