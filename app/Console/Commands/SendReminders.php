<?php

namespace App\Console\Commands;

use App\Jobs\SendRemindersJob;
use App\Models\Reminder;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia los recordatorios que cada usuario ha configurado en su perfil';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $reminders = Reminder::withoutGlobalScopes("forCustomer")
            ->with("user")
            ->where("reminder_timezone_date", "<=", now()->format("Y-m-d H:i:s"))
            ->whereNull("notified_at")
            ->orderBy("reminder_timezone_date")
            ->get();

        if ($reminders->count() > 0) {
            foreach ($reminders as $reminder) {
                // Vamos a enviar el recordatorio medante un Job, en la cola "emails"
                // Basicamente cada Job se va a encolar en la cola "emails"
                dispatch(new SendRemindersJob($reminder))->onQueue("emails");
            }
        }
    }
}
