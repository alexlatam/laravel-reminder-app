<div>
    {{-- Este div contendra el calendario --}}
    {{-- wire:ignore Evita que se recargue el componente --}}
    <div id='reminders-calendar' wire:ignore class="bg-white p-6 rounded-lg shadow-lg"></div>
    <span wire:loading>
        <livewire:spinner />
    </span>

    @push('styles')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
        <style>
            .fc-daygrid-dot-event .fc-event-title {
                flex-grow: 1;
                flex-shrink: 1;
                min-width: 0;
                overflow: hidden;
                font-weight: 700;
                white-space: normal;
            }
            .fc-h-event .fc-event-title {
                display: inline-block;
                vertical-align: top;
                left: 0;
                right: 0;
                max-width: 100%;
                overflow: hidden;
                white-space: normal;
            }
            .fc-event-time {
                display: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.5/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('reminders-calendar');
                var calendar   = new FullCalendar.Calendar(calendarEl, {
                    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                    locale: "{{ config('app.locale') }}",
                    firstDay: 0,
                    height: "auto",
                    titleFormat: { year: 'numeric', month: 'long', day: 'numeric' },
                    initialView: 'dayGridWeek',
                    events: function(info, succesCallback, failureCallback) {
                        window.livewire.emit('loadWeek', info.start, info.end);
                        window.livewire.on('refreshReminders', () => {
                            succesCallback(JSON.parse(@this.reminders));
                        });
                    },
                    eventClick: function(info) {
                        window.location.href = `/reminders/${info.event.id}/edit`;
                    },
                    buttonText: {
                        today: "{{ __('Hoy') }}",
                        month: "{{ __('Mes') }}",
                        week: "{{ __('Semana') }}",
                        day: "{{ __('Día') }}",
                        list: "{{ __('Lista') }}",
                    },
                });
                calendar.render();
            });
        </script>
    @endpush

</div>
