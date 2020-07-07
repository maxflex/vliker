<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => ['required', 'exists:tasks,id']
        ]);

        auth()->user()->reports()->create($request->all());
    }
}
