<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Recordatorios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center items-start mx-auto">
                <a class="flex-shrink-0 text-white bg-indigo-500 border-0 py-2 px-8" href="{{ route('reminders.create') }}">
                    {{ __('Añadir Recordatorio') }}
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <livewire:reminder.calendar />
        </div>
    </div>
</x-app-layout>
