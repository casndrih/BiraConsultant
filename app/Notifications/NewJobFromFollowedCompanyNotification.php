<?php

namespace App\Notifications;

use App\Models\BiraConsultantJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobFromFollowedCompanyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BiraConsultantJob $job
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->notify_email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $companyName = $this->job->company->name ?? 'A company you follow';

        return (new MailMessage)
            ->subject('New job from ' . $companyName . ': ' . $this->job->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('**' . $companyName . '** just posted a new job you might be interested in.')
            ->line('**Position:** ' . $this->job->title)
            ->line('**Location:** ' . ($this->job->location ?? 'Not specified'))
            ->action('View Job', route('job.detail', $this->job->slug))
            ->line('You received this because you follow this company on BiraConsultant Job Board.')
            ->line('Manage your notification preferences in your account settings.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_job_followed_company',
            'job_id' => $this->job->id,
            'job_slug' => $this->job->slug,
            'job_title' => $this->job->title,
            'company_id' => $this->job->company_id,
            'company_slug' => $this->job->company->slug ?? null,
            'company_name' => $this->job->company->name ?? 'Unknown',
            'message' => $this->job->company->name . ' posted a new job: ' . $this->job->title,
        ];
    }
}