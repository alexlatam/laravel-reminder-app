<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Horario en que se ejecutara el comando "reminders:send-reminders"
        // Cada minuto se ejecutara este comando que a su vez ejecutara un Job
        // El Job se encargara de enviar los recordatorios mediante una Notificacion
        // El canal de notificacion es el canal de correo electronico
        $schedule->command('reminders:send-reminders')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
