<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>

        <br>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('phonebooks.index') }}">
                <div class="bg-green overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 w3-light-grey w3-hover-light-blue w3-leftbar border-b w3-border-blue">
                        View your phonebooks
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
