<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Course') }}
        </h2>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        </h2>    
                    <form method="POST" class="space-y-6" action="{{ route('courses.store') }}">
                            @csrf
                            @method('POST')

                            <div>
                                <x-breeze.input-label for="language" :value="__('Language')" />
                                <x-breeze.text-input id="language" name="language" type="text" class="mt-1 block w-full" :value="old('language')" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('language')" />
                            </div>

                            <div>
                                <x-breeze.input-label for="level" :value="__('Level')" />
                                <x-breeze.text-input id="level" name="level" type="text" class="mt-1 block w-full" :value="old('level')" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('level')" />
                            </div>

                            <div>
                                <x-breeze.input-label for="schedule" :value="__('Schedule')" />
                                <x-breeze.text-input id="schedule" name="schedule" type="text" class="mt-1 block w-full" :value="old('schedule')" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('schedule')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-breeze.primary-button>{{ __('Save') }}</x-breeze.primary-button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
