<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>

        @if($updating && $reminder->canDelete())
            <form method="POST" action="{{ route("reminders.destroy", ["reminder" => $reminder->id]) }}">
                @method("DELETE")
                @csrf
                {!! FormFacade::submit(__("Eliminar recordatorio"), ["class" => "float-right text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded text-lg -mt-8"]) !!}
            </form>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="w-full px-5 mx-auto">
            <livewire:reminder.form :text-button="$textButton" :reminder="$reminder" :updating="$updating" />
        </div>
    </div>
</x-app-layout>
