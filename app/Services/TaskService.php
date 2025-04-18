<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function createTask($data)
    {
        return Task::create($data);
    }

    public function assignTask($taskId, $userId){

        $task = Task::findOrFail($taskId);
        $task->update(['assigned_to' => $userId]);
        return $task;
    }

    public function completeTask($taskId){
        $task = Task::findOrFail($taskId);
        $task->update(['status' => 'completed']);
        event(new \App\Events\TaskCompleted($task));
        return $task;
    }

    public function listTasks($filters){

        $query = Task::query();

        if(isset($filters['status'])){
            $query->where('status',$filters['status']);
        }

        if(isset($filters['assigned_to'])){
            $query->where('assigned_to',$filters['assigned_to']);
        }

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%'); 
        }
    
        if (isset($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']); 
        }

        return $query->paginate(10);
    }
}