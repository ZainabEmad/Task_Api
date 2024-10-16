<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class TaskController extends Controller
{
    //
    use ApiResponseTrait;

    public function index()
    {
        $tasks = Task::paginate(2);
        $tasksResource = TaskResource::collection($tasks);
        return $this->apiresponse($tasksResource, 'ok', 200);
    }
    // #################################################

    public function show($id){
        $task = new TaskResource(Task::find($id));
        if($task){
            return $this->apiresponse($task, 'ok', 200);
        }
        return $this->apiresponse(null, 'this task not found', 401);
    }
// #######################################################

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'priority_level' => 'required|integer|min:1|max:5',
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 400);
        }
    
        try {
            $task = Task::create($request->all());
            if ($task) {
                return response()->json([
                    'message' => 'Task created successfully!',
                    'task' => $task
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Failed to create task'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create task: '.$e->getMessage());
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // #################################################
    
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'priority_level' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 400);
        }

        $task = Task::find($id);
        if(!$task){
            return $this->apiresponse(null, 'this task not found', 404);
        }
        $task->update($request->all());
        if($task){
            return $this->apiresponse($task, 'this task updated', 201);
        }
        return $this->apiresponse(null, 'Failed to update task', 500);
    }
// ######################################################

    public function destroy($id){
        $task = Task::find($id);
        if(!$task){
            return $this->apiresponse(null, 'this task not found', 404);
        }
        $task->delete();
        return $this->apiresponse(null, 'this task deleted', 200);
    }
// ####################################################

    public function markAsCompleted($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->status = true;
            $task->save();
            return $this->apiresponse($task, 'This task is completed', 201);
        }
        return $this->apiresponse(null, 'Failed to complete the task', 500);
    }

// #####################################################

    public function markAsPending($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->status = false;
            $task->save();
            return $this->apiresponse($task, 'This task is pending', 201);
        }
        return $this->apiresponse(null, 'Failed to set the task as pending', 500);
    }
    // ######################################################

    public function filterTasks(Request $request){
        $title = $request->query('title');
        $due_date = $request->query('due_date');
        $priority_level = $request->query('priority_level');
        $query = Task::query();
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }
        if($due_date){
            $query->whereDate('due_date', $due_date);
        }
        if($priority_level){
            $query->where('priority_level', $priority_level);
        }
        $tasks = $query->get();
        if ($tasks->isEmpty()) {
            return $this->apiresponse(null, 'No tasks found', 404); // Change status code to 404
        }
            $tasksResource = TaskResource::collection($tasks);
            return $this->apiresponse($tasksResource, 'ok', 200);
        }
}