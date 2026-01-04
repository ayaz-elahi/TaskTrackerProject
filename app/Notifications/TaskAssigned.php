<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Task Assigned: ' . $this->task->title)
                    ->line('You have been assigned a new task' . ($this->task->project ? ' in project: ' . $this->task->project->name : ''))
                    ->action('View Task', route('tasks.show', $this->task))
                    ->line('Please check the dashboard for details.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'project_id' => $this->task->project_id,
        ];
    }
}
