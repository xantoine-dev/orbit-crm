<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService
{
    public function tasksByProject(int $projectId): Collection
    {
        return Task::with(['project', 'assignee'])
            ->where('project_id', $projectId)
            ->orderBy('due_date')
            ->get();
    }

    public function tasksByUser(int $userId): Collection
    {
        return Task::with(['project', 'assignee'])
            ->where('assigned_to', $userId)
            ->orderBy('due_date')
            ->get();
    }

    public function overdueTasks(): Collection
    {
        return Task::with(['project', 'assignee'])
            ->where('status', '!=', 'done')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->get();
    }

    public function toCsv(string $filename, Collection $tasks): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($tasks) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Task', 'Project', 'Assignee', 'Due Date', 'Status']);
            foreach ($tasks as $task) {
                fputcsv($output, [
                    $task->title,
                    optional($task->project)->title,
                    optional($task->assignee)->name,
                    optional($task->due_date)?->toDateString(),
                    $task->status,
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function toPdf(string $title, Collection $tasks): StreamedResponse
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('reports.pdf', [
            'title' => $title,
            'tasks' => $tasks,
        ])->render());
        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, str($title)->snake() . '.pdf', ['Content-Type' => 'application/pdf']);
    }
}
