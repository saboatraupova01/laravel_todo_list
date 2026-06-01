<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $tasksQuery = Task::query();

        if ($filter === 'active') {
            $tasksQuery->where('is_done', false);
        }

        if ($filter === 'done') {
            $tasksQuery->where('is_done', true);
        }

        $tasks = $tasksQuery->latest()->get();

        $total = Task::count();
        $active = Task::where('is_done', false)->count();
        $done = Task::where('is_done', true)->count();

        return view('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'total' => $total,
            'active' => $active,
            'done' => $done,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        Task::create([
            'title' => $request->title,
            'is_done' => false
        ]);

        return redirect('/');
    }

    public function update(Task $task)
    {
        $task->is_done = !$task->is_done;
        $task->save();

        return redirect('/');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'is_done' => false
        ]);

        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'is_done' => $task->is_done
        ]);
    }
}
