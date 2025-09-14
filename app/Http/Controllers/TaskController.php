<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Task;
use App\Exports\TasksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'due_date' => 'required|date|after_or_equal:today',
                'time_tracked' => 'nullable|integer|min:0',
                'status' => 'nullable|in:pending,in_progress,completed',
                'priority' => 'nullable|in:low,medium,high',
                'user_id' => 'nullable|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task = Task::create([
                'title' => $request->title,
                'due_date' => $request->due_date,
                'time_tracked' => $request->time_tracked ?? 0,
                'status' => $request->status,
                'priority' => $request->priority,
                'user_id' => $request->user_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportTasks(Request $request)
    {
        try {
            $fileName = 'tasks_report_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new TasksExport($request), $fileName);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChartData(Request $request)
    {
        try {
            $type = $request->query('type');

            if (!$type) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing type parameter'
                ], 400);
            }

            switch ($type) {
                case 'status':
                    $data = Task::select('status', DB::raw('count(*) as total'))
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

                    return response()->json([
                        'status_summary' => [
                            'pending' => $data['pending'] ?? 0,
                            'open' => $data['open'] ?? 0,
                            'in_progress' => $data['in_progress'] ?? 0,
                            'completed' => $data['completed'] ?? 0,
                        ]
                    ]);

                case 'priority':
                    $data = Task::select('priority', DB::raw('count(*) as total'))
                        ->groupBy('priority')
                        ->pluck('total', 'priority')
                        ->toArray();

                    return response()->json([
                        'priority_summary' => [
                            'low' => $data['low'] ?? 0,
                            'medium' => $data['medium'] ?? 0,
                            'high' => $data['high'] ?? 0,
                        ]
                    ]);

                case 'assignee':
                    $users = \App\Models\User::with(['tasks'])->get();

                    $assigneeSummary = [];
                    foreach ($users as $user) {
                        $totalTodos = $user->tasks->count();
                        $totalPending = $user->tasks->where('status', 'pending')->count();
                        $totalTimeTrackedCompleted = $user->tasks
                            ->where('status', 'completed')
                            ->sum('time_tracked');

                        $assigneeSummary[$user->name] = [
                            'total_todos' => $totalTodos,
                            'total_pending_todos' => $totalPending,
                            'total_timetracked_completed_todos' => $totalTimeTrackedCompleted,
                        ];
                    }

                    return response()->json([
                        'assignee_summary' => $assigneeSummary
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type parameter'
                    ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
