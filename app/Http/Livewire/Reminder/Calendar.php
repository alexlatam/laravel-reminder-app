<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Contracts\Support\Renderable;

class Calendar extends Component
{
    const DEFAULT_COLOR      = "#2F5AD8";
    const NOTIFIED_COLOR     = "#13B176";
    const NOT_NOTIFIED_COLOR = "#F78B00";

    public string $reminders;
    public Carbon $fromDate;
    public Carbon $toDate;

    protected $listeners = ['loadWeek'];

    // Al momento de renderizar el componente mostramos la semana actual
    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfWeek();
        $this->toDate   = Carbon::now()->endOfWeek();
    }

    public function render(): Renderable
    {
        return view('livewire.reminder.calendar');
    }

    /**
     * Permite mostrar la semana seleccionada en el calendario
     *
     * @param string $from
     * @param string $to
     * @return void
     * @paramrefreshReminders string $to
     */
    public function loadWeek(string $from, string $to): void
    {
        $fromParts = explode("T", $from);
        $this->fromDate = Carbon::createFromDate($fromParts[0]);
        $toParts = explode("T", $to);
        $this->toDate = Carbon::createFromDate($toParts[0]);
        $this->getReminders();
        $this->emit("refreshReminders");
    }

    protected function getReminders(): void
    {
        $reminders = Reminder::query()
            ->select(["id", "reminder_text", "reminder_day", "reminder_hour", "notified_at"])
            ->whereBetween("reminder_day", [
                $this->fromDate->format("Y-m-d"), $this->toDate->format("Y-m-d")
            ])
            ->orderBy("reminder_day")
            ->orderBy("reminder_hour")
            ->get();

        if (!$reminders->count()) {
            $this->reminders = $reminders->toJson();
            return;
        }

        $collection = collect();
        foreach ($reminders as $reminder) {
            $backgroundColor = self::DEFAULT_COLOR;
            // si el día de recordatorio es menor a hoy
            if ($reminder->reminder_day?->lte(now())) {
                $backgroundColor = $reminder->notified_at ? self::NOTIFIED_COLOR : self::NOT_NOTIFIED_COLOR;
            }

            $collection->push([
                "id" => $reminder->id,
                "title" => $reminder->reminder_hour_formatted . " - " . $reminder->reminder_text,
                "start" => $reminder->reminder_day,
                "backgroundColor" => $backgroundColor,
            ]);
        }

        $this->reminders = $collection->toJson();
    }
}
