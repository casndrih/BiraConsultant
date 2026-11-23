<?php

namespace App\Notifications;

use App\Models\QJobsCompany;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public QJobsCompany $company
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
        return (new MailMessage)
            ->subject('You are now following ' . $this->company->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are now following **' . $this->company->name . '** on QJobs Job Board.')
            ->line('You will receive notifications when this company posts new job opportunities.')
            ->action('View Company', route('company.detail', $this->company->id))
            ->line('You can manage your notification preferences in your account settings.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'company_followed',
            'company_id' => $this->company->id,
            'company_slug' => $this->company->slug,
            'company_name' => $this->company->name,
            'message' => 'You are now following ' . $this->company->name,
        ];
    }
}