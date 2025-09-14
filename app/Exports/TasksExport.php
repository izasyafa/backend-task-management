<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class TasksExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Task::with('user');

        if ($title = $this->request->get('title')) {
            $query->where('title', 'like', "%$title%");
        }

        if ($assignee = $this->request->get('assignee')) {
            $assignees = explode(',', $assignee);
            $query->whereHas('user', function ($q) use ($assignees) {
                $q->whereIn('name', $assignees);
            });
        }

        if ($this->request->has(['start', 'end'])) {
            $query->whereBetween('due_date', [
                $this->request->get('start'),
                $this->request->get('end')
            ]);
        }

        if ($this->request->has(['min', 'max'])) {
            $query->whereBetween('time_tracked', [
                $this->request->get('min'),
                $this->request->get('max')
            ]);
        }

        if ($status = $this->request->get('status')) {
            $statuses = explode(',', $status);
            $query->whereIn('status', $statuses);
        }

        if ($priority = $this->request->get('priority')) {
            $priorities = explode(',', $priority);
            $query->whereIn('priority', $priorities);
        }

        $tasks = $query->get();

        $summary = [
            'total_tasks' => $tasks->count(),
            'total_time_tracked' => $tasks->sum('time_tracked'),
        ];

        return view('exports.tasks', compact('tasks', 'summary'));
    }
}
