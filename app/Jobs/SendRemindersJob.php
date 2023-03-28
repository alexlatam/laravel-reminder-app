<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Notifications\NewReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reminder $reminder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Notificamos al usuario que tiene el recordatorio
        $this->reminder?->user->notify(new NewReminderNotification($this->reminder));
    }
}
