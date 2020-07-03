<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task\ReportedTask;

class ReportsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'reported_task_id' => ['required', 'exists:tasks,id']
        ]);

        return auth()->user()->reportedTasks()->create($request->all());
    }
}
