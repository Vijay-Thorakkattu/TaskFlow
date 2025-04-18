<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Task;
use App\Jobs\SendTaskAssignedEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create(Request $request)
    {
        $rules = [
            'title'       => 'required|string',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status'      => 'nullable|in:pending,completed,expired',
            'due_date' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:' . now()->toDateTimeString(),
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $validatedData = $validator->validated();
            $task = $this->taskService->createTask($validatedData);
    
            return response()->json([
                'success' => true,
                'data'    => $task,
            ], 201);
    
        } catch (\Exception $e) {    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the task.',
            ], 500);
        }
    }

    public function assign($id, Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'assigned_to' => 'required|exists:users,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }
    
            $validated = $validator->validated();
    
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found.',
                ], 404);
            }
    
            if ($task->assigned_to) {
                $currentUser = $task->assignedTo; 
                return response()->json([
                    'success' => false,
                    'message' => 'Task is already assigned to another user.',
                    'assigned_to' => [
                        'id'    => $currentUser->id ?? $task->assigned_to,
                        'name'  => $currentUser->name,
                        'email' => $currentUser->email,
                    ],
                ], 400);
            }
    
            $task = $this->taskService->assignTask($id, $validated['assigned_to']);
            $user = $task->assignedTo;
            SendTaskAssignedEmail::dispatch($task, $user);
    
            return response()->json([
                'success' => true,
                'message' => 'Task successfully assigned.',
                'data'    => $task,
            ], 200);

        } catch (\Exception $e) {    
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while assigning the task.',
            ], 500);
        }
    }
    

    public function complete($id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found.',
                ], 404);
            }

            if (!$task->assignedTo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task is not assigned to any user.',
                ], 400);
            }

            if ($task->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task is already marked as completed.',
                    'data'    => $task,
                ], 400);
            }

            $task = $this->taskService->completeTask($id);
            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed.',
                'data'    => $task,
            ], 200);
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while completing the task.'
            ], 500);
        }
    }


    public function list(Request $request){

        try {

            $filter = $request->only(['status', 'assigned_to', 'title', 'due_date']);
            $tasks = $this->taskService->listTasks($filter);
    
            return response()->json([
                'success' => true,
                'data'    => $tasks,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the tasks.',
            ], 500);
        }
    }
}
