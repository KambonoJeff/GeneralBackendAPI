<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;
use App\Http\Requests\StoreTaskRequest;
use App\Traits\HttpResponses;

class TaskController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TasksResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'name'=> ['required','string','max:255'],
            'description'=>['required'],
            'priority'=>['required']

        ]);
        $task = Task::create([
            'user_id' => Auth::user()->id,
            'name' => $field['name'],
            'description' =>  $field['description'],
            'priority'=>$field['priority']
        ]);
        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if(Auth::user()->id !== $task->user_id){
            return $this->error('','you are not authorized to make this request', 403);
        }
        return  new TasksResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if(Auth::user()->id !== $task->user_id){
            return $this->error('','you are not authorized to make this request', 403);
        }
        $task->update($request->all());
        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response(null,204);
    }
}
