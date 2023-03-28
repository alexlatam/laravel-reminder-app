<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class Form extends Component
{
    public Reminder $reminder;
    public string $day = '';
    public string $textButton;
    public bool $updating = false;

    public function mount(): void
    {

        if($this->updating){
            $this->day = $this->reminder->reminder_day->format("d-m-Y");
            session()->put("reminder:day_for_update", $this->reminder->reminder_day->format("d-m-Y"));
        } else {
            $this->reminder->reminder_timezone = config("app.timezone");
            session()->forget("reminder:day_for_update");
        }
    }

    public function rules(): array
    {
        return [
            "day" => "nullable|date_format:d-m-Y",
            "reminder.reminder_day" => "nullable",
            "reminder.reminder_timezone" => "nullable",
            "reminder.reminder_hour" => "required|date_format:H:i",
            "reminder.reminder_text" => "required|string|min:2|max:200",
        ];
    }

    public function render()
    {
        return view('livewire.reminder.form');
    }

    public function store(): RedirectResponse|Redirector|null {
        if (!$this->checkReminderDayValue()) return null;

        $day = $this->getDay();

        $check = Reminder::whereReminderDay($day)
            ->whereReminderHour($this->reminder->reminder_hour)
            ->exists();

        if ($check) {
            $this->reminderDuplicated();
            return null;
        }

        $remindersPerDay = Reminder::whereReminderDay($day)->count();

        if ($remindersPerDay >= Reminder::MAX_PER_DAY) {
            $this->reminderExceeded();
            return null;
        }

        $message = __("El Recordatorio ha sido creado correctamente");
        return $this->saveReminder($day, $message);
    }

    public function update(): RedirectResponse|Redirector|null
    {
        if (!$this->checkReminderDayValue()) return null;

        $day = $this->getDay();

        $check = Reminder::whereReminderDay($day)
            ->whereReminderHour($this->reminder->reminder_hour)
            ->where("id", "!=", $this->reminder->id)
            ->exists();

        if ($check) {
            $this->reminderDuplicated();
            return null;
        }

        if ($this->day !== session("reminder:day_for_update")) {
            $remindersPerDay = Reminder::whereReminderDay($day)->count();
            if ($remindersPerDay >= Reminder::MAX_PER_DAY) {
                $this->reminderExceeded();
                return null;
            }
        }

        $message = __("El Recordatorio ha sido actualizado correctamente");
        return $this->saveReminder($day, $message);
    }

    protected function getDay(): string
    {
        return Carbon::createFromFormat('d-m-Y', $this->day)->format('Y-m-d');
    }

    // Validacion personalizada para el campo calendario del formulario
    protected function checkReminderDayValue(): ?bool
    {
        if (! $this->reminder->reminder_day) {
            $this->emit("reminderDayValidationFailed", __("El dia de recordatorio es obligatorio"));
            return null;
        }
        return true;
    }

    // Se ejecuta si el usuario tiene un recordatorio a esa misma hora y dia
    protected function reminderDuplicated(): void
    {
        $this->emit("reminderError", __("Ya existe un recordatorio para el :day a las :hour", [
            "day" => $this->day,
            "hour" => $this->reminder->reminder_hour->format("H:i")
        ]));
    }

    // Se ejecuta si el usuario tiene mas de 3 recordatorios en el dia
    protected function reminderExceeded(): void
    {
        $this->emit("reminderError", __('ya tienes :max_reminders_per_day recordatorios para el dia :day, no es posible crear mas de :max_reminders_per_day recordatorios por dia', [
            "max_reminders_per_day" => Reminder::MAX_PER_DAY,
            "day" => $this->day,
        ]));
    }

    //  Retorna la fecha y hora del recordatorio con el timezone de la app
    protected function dateTimeStringWithTimezone(): string
    {
        $dateTime = Carbon::createFromFormat("d-m-Y H:i", sprintf("%s %s", $this->day, $this->reminder->reminder_hour->format("H:i")));
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $this->reminder->reminder_timezone);
        $date->setTimezone(config("app.timezone"));
        return Carbon::parse($date)->toDateTimeString();
    }

    // Actualiza o crea el modelo
    protected function upsertModel(string $date, string $day): void {
        // fecha en la que notificaremos al usuario
        $this->reminder->reminder_timezone_date = $date;

        // día seleccionado por el usuario, puede diferir con la fecha de notificación por la zona horaria
        $this->reminder->reminder_day = $day;

        // actualizamos modelo
        $this->reminder->save();
    }


    // Guarda el recordatorio
    protected function saveReminder(string $day, string $message): RedirectResponse|Redirector {
        $this->validate();

        $date = $this->dateTimeStringWithTimezone();

        $this->upsertModel($date, $day);

        session()->flash('success', $message);

        return redirect()->to(route("reminders.index"));
    }


}
