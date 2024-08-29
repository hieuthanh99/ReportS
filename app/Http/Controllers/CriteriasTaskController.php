<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriteriasTask;

class CriteriasTaskController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $criteriasTasks = CriteriasTask::where('isDelete', 0)->get();;
        return view('criterias_task.index', compact('criteriasTasks'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('criterias_task.create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'TaskID' => 'nullable|integer',
            'CriteriaID' => 'nullable|integer',
            'CriteriaCode' => 'nullable|string|max:255',
            'CriteriaName' => 'nullable|string|max:255',
            'CreatedBy' => 'nullable|string|max:256',
            'CreatedDTG' => 'nullable|date',
            'UpdatedBy' => 'nullable|string|max:256',
            'UpdatedDTG' => 'nullable|date',
            'DocumentID' => 'nullable|integer',
            'TaskCode' => 'nullable|string|max:50',
            'RequestResult' => 'nullable|string',
        ]);

        CriteriasTask::create($request->all());
        return redirect()->route('criterias_task.index')
                         ->with('success', 'Criteria Task created successfully.');
    }

    // Display the specified resource
    public function show($id)
    {
        $criteriasTask = CriteriasTask::findOrFail($id);
        return view('criterias_task.show', compact('criteriasTask'));
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        $criteriasTask = CriteriasTask::findOrFail($id);
        return view('criterias_task.edit', compact('criteriasTask'));
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'TaskID' => 'nullable|integer',
            'CriteriaID' => 'nullable|integer',
            'CriteriaCode' => 'nullable|string|max:255',
            'CriteriaName' => 'nullable|string|max:255',
            'CreatedBy' => 'nullable|string|max:256',
            'CreatedDTG' => 'nullable|date',
            'UpdatedBy' => 'nullable|string|max:256',
            'UpdatedDTG' => 'nullable|date',
            'DocumentID' => 'nullable|integer',
            'TaskCode' => 'nullable|string|max:50',
            'RequestResult' => 'nullable|string',
        ]);

        $criteriasTask = CriteriasTask::findOrFail($id);
        $criteriasTask->update($request->all());
        return redirect()->route('criterias_task.index')
                         ->with('success', 'Criteria Task updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $criteriasTask = CriteriasTask::findOrFail($id);
        $criteriasTask->delete();
        return redirect()->route('criterias_task.index')
                         ->with('success', 'Criteria Task deleted successfully.');
    }
}