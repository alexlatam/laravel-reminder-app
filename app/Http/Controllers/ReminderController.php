<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class ReminderController extends Controller
{
    public function index(): Renderable
    {
        Carbon::setLocale(config('app.locale'));
        return view('reminders.index');
    }

    public function create(): Renderable
    {
        return view('reminders.form', [
            'reminder'   => new Reminder,
            'title'      => __('Crear nuevo recordatorio'),
            'updating'   => false,
            'textButton' => __('Crear recordatorio'),
        ]);
    }

    public function edit(int $id): Renderable
    {
        $reminder = Reminder::findOrFail($id);
        return view('reminders.form', [
            'reminder'   => $reminder,
            'title'      => __('Editar recordatorio'),
            'updating'   => true,
            'textButton' => __('Actualizar recordatorio'),
        ]);
    }

    public function destroy(Reminder $reminder): RedirectResponse
    {
        $reminder->delete();
        return redirect()->to(route('reminders.index'));
    }
}
