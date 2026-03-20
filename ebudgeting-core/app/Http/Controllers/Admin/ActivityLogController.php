<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Activity::with('causer');

        if ($search) {
            $query->where('description', 'ilike', "%{$search}%")
            ->orWhereHas('causer', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                    });
        }

        $logs = $query->latest()->paginate(15);

        return view('admin.logs.index', compact('logs', 'search'));
    }
}
