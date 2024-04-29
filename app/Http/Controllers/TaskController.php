<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('Task.index', compact('tasks'));
    }

    public function create()
    {
        return view('Task.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'integer|min:1',
        'due_date' => 'required|date',
        'completed' => 'boolean',
    ]);

    $task = Task::create($validatedData);

    return response()->json(['message' => 'Task added successfully', 'task' => $task], 200);
}



    public function edit(Task $task)
    {
        return view('Task.edit', compact('task'));
    }

    public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'integer|min:1',
        'due_date' => 'required|date',
        'completed' => 'boolean',
    ]);

    $task->update($validatedData);

    return response()->json(['message' => 'Task updated successfully']);
}

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
    
    
}