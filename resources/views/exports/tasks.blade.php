<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Assignee</th>
            <th>Due Date</th>
            <th>Time Tracked</th>
            <th>Status</th>
            <th>Priority</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ $task->user->name ?? '-' }}</td>
            <td>{{ $task->due_date }}</td>
            <td>{{ $task->time_tracked }}</td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->priority }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="2"><strong>Total Todos: {{ $summary['total_tasks'] }}</strong></td>
            <td colspan="2"><strong>Total Time Tracked: {{ $summary['total_time_tracked'] }}</strong></td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>
