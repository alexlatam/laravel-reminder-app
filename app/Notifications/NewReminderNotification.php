<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Reminder $reminder)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];  // Esto indica que la notificacion se enviara por email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $this->reminder->notified_at = now();
            $this->reminder->save();

            return (new MailMessage)
                        ->subject('Nuevo recordatorio')
                        ->line(__("Fecha del recordatorio: :date", ["date" => $this->reminder->date_formatted]))
                        ->line($this->reminder->reminder_text)
                        ->line(__('Cracias por usar la plataforma!'));

        } catch (\Throwable $th) {
            $this->reminder->notified_at = null;
            $this->reminder->save();
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
