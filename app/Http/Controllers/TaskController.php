<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(int $id)
    {
        // すべてのフォルダを取得する
        $user = Auth::user();
        $folders = Folder::where('user_id','=',$user->id)->get();
        logger()->debug($folders);

        // 選ばれたフォルダを取得する
        $current_folder = Folder::find($id);
        if (is_null($current_folder)) {
            abort(404);
        }
        if (Auth::user()->id !== $current_folder->user_id) {
            abort(403);
        }
        // 選ばれたフォルダに紐づくタスクを取得する
        $tasks = $current_folder->tasks()->get();

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $current_folder->id,
            'tasks' => $tasks,
        ]);
    }

    public function showCreateForm(int $id)
    {
        $current_folder = Folder::find($id);
        if (is_null($current_folder)) {
            abort(404);
        }
        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {

        $current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $current_folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }

    public function showEditForm(int $id, int $task_id)
    {
        $task = Task::find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
        $task = Task::find($task_id);
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }
}
