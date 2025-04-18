<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;

class TaskResourceController extends Controller
{
    //

    public function getTransformedTasks(Request $request){

        try {
          $tasks = Task::with('assignedTo')->paginate(10);
          return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching tasks.',
            ], 500);
        }
    }

    public function show($id)
    {
        $task = Task::with('assignedTo')->findOrFail($id); 
        return new TaskResource($task);
    }
}
