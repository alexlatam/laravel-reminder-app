<div>
    <form wire:submit.prevent="{{ $updating ? 'update' : 'store' }}">

        <div class="container px-5 mx-auto">

            <!-- SI HAY UN ERROR QUE NO ES DE VALIDACIÓN SE MOSTRARÁ AQUÍ -->
            <div id="reminder_error" class="bg-red-500 text-white p-5 text-center hidden"></div>

            <div class="bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
                <!-- Select zonas horarias -->
                <div class="relative mb-4" wire:ignore>
                    <label class="form-label flex flex-col sm:flex-row" for="timezones">{{ __("Zona horaria") }}</label>
                    <select id="timezone" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" data-placeholder="{{ __("Selecciona tu zona horaria") }}">
                        @foreach(DateTimeZone::listIdentifiers() as $timezone)
                            <option {{ $reminder->reminder_timezone === $timezone ? "selected" : "" }} value="{{ $timezone }}">{{ $timezone }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- ./Select zonas horarias -->

                <!-- Día -->
                <div class="relative mb-4" wire:ignore>
                    <label for="reminder_day" class="form-label w-full flex flex-col sm:flex-row">
                        {{ __("Día") }}
                    </label>
                    {!! FormFacade::input('text', 'reminder_day', old('reminder_day'), ['class' => 'reminder_day w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out' . ($errors->has('reminder_day') ? ' border-2 border-red-500' : null), 'id' => 'reminder_day', 'required' => true]) !!}
                    <div class="text-red-500 mt-2 hidden" id="reminder_day_error"></div>
                </div>
                <!-- ./Día -->

                <!-- Hora -->
                <div class="relative mb-4">
                    <label for="reminder_hour" class="form-label w-full flex flex-col sm:flex-row">
                        {{ __("Hora") }}
                    </label>
                    {!! FormFacade::input('text', 'reminder_hour', old('reminder_hour'), ["wire:model.debounce.200ms" => "reminder.reminder_hour", 'class' => 'reminder_hour w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out' . ($errors->has('reminder_hour') ? ' border-2 border-red-500' : null), 'id' => 'reminder_hour', 'required' => true]) !!}

                    @error('reminder.reminder_hour')
                        <div class="text-red-500">
                            {{ $errors->first('reminder.reminder_hour') }}
                        </div>
                    @enderror
                </div>
                <!-- ./Hora -->

                <!-- Comentarios -->
                <div class="relative mb-4 w-full">
                    <label for="reminder_text" class="form-label w-full flex flex-col sm:flex-row">
                        {{ __("Comentarios") }}
                    </label>
                    {!! FormFacade::textarea('reminder_text', old('reminder_text'), ['wire:model.debounce.300ms' => 'reminder.reminder_text', 'class' => 'w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out' . ($errors->has('reminder_text') ? ' border-2 border-red-500' : null), 'id' => 'reminder_text', 'placeholder' => __("Escribe aquello que necesitas recordar"), 'minlength' => 2]) !!}

                    @error('reminder.reminder_text')
                        <div class="text-red-500">
                            {{ $errors->first('reminder.reminder_text') }}
                        </div>
                    @enderror
                </div>
                <!-- ./Comentarios -->
                <div class="flex flex-wrap align-middle">
                    {!! FormFacade::submit($textButton, ["class" => "text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg mt-2 mr-4 cursor-pointer"]) !!}
                    <a href="{{route('reminders.index')}}" class="text-black bg-gray-200 border-0 py-2 px-6 focus:outline-none hover:bg-gray-300 rounded text-lg mt-2">Regresar</a>
                </div>

            </div>
        </div>
    </form>
</div>

@push("styles")
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone.min.js" integrity="sha512-jkvef+BAlqJubZdUhcyvaE84uD9XOoLR3e5GGX7YW7y8ywt0rwcGmTQHoxSMRzrJA3+Jh2T8Uy6f8TLU3WQhpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        window.jQuery(document).ready(() => {
            window.jQuery("#timezone").on("change", (e) => {
                @this.set("reminder.reminder_timezone", e.target.value)
            })

            window.jQuery('#timezone').select2();

            flatpickr(".reminder_day", {
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                locale: "es",
                defaultDate: '{{ $updating ? $reminder->reminder_day->format("Y-m-d") : null }}',
                minDate: '{{ $updating ? $reminder->reminder_day : now()->addDay() }}',
                formatDate: (date) => {
                    const formatDate = moment.tz(date, '{{ config("app.timezone") }}')
                    return formatDate.format("DD-MM-Y");
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if(dateStr) {
                        @this.set("reminder.reminder_day", dateStr)
                        @this.set("day", dateStr)
                        window.jQuery("#reminder_day_error").text('').hide()
                    }
                },
            })

            flatpickr(".reminder_hour", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                defaultDate: '{{ $updating ? $reminder->reminder_hour : null }}',
                time_24hr: true,
                locale: "es",
            })

            window.livewire.on("reminderDayValidationFailed", (message) => {
                window.jQuery("#reminder_day_error").text(message).show()
            })

            window.livewire.on("reminderError", (message) => {
                window.jQuery("#reminder_error").text(message).show()
                setTimeout(() => {
                    window.jQuery("#reminder_error").text('').hide()
                }, 5000)
            })

        })
    </script>
@endpush
