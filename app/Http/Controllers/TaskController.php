<?php

namespace App\Http\Controllers;

use App\Mail\TaskMail;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ItemUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    // public function list()
    // {
    //     $user = Auth::user();
    //     $tasks = Task::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
    //     $i = 0;
    //     return view('task.list', compact('tasks', 'i'));
    // }

    public function show($id)
    {
        $user = Auth::user();
        $task = $user->task->where('id', $id)->first();
        if ($task) {
            return view('task.show', compact('task'));
        } else {
            return redirect('dashboard')->with('error', 'invalid request!');
        }
    }

    public function add_form()
    {
        return view('task.add');
    }

    public function update_form($id)
    {
        $user = Auth::user();
        $task = $user->task->where('id', $id)->first();
        if ($task) {
            return view('task.update', compact('task'));
        } else {
            return redirect('dashboard')->with('error', 'invalid request!');
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'due_date' => ['required', 'date', 'after:' . Date::tomorrow()],
        ]);

        $user = Auth::user();

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        Mail::to($user->email)->send(new TaskMail($task));

        return redirect('dashboard')->with('success', 'Task successfullly Added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'due_date' => ['required', 'date'],
        ]);
        $user = Auth::user();
        $task = $user->task->where('id', $id)->first();

        $task->update([
            'user_id' => $task->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);


        $user = User::find($user->id);
        $user->notify(new ItemUpdatedNotification($task));
        return redirect('dashboard')->with('success', 'Task Updated successfully!');
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function delete($id)
    {
        // $task = Task::findorFail($id);
        $user = Auth::user();
        $task = $user->task->where('id', $id)->first();
        if ($task) {
            $task->delete();
            return redirect('dashboard')->with('success', 'Task deleted successfully!');
        } else {
            return redirect('dashboard')->with('error', 'invalid request!');
        }
    }
}
