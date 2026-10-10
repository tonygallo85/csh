<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" class="space-y-6" action="{{ route('courses.update', $course) }}">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-breeze.input-label for="language" :value="__('Language')" />
                                <x-breeze.text-input id="language" name="language" type="text" class="mt-1 block w-full" :value="old('language', $course->language)" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('language')" />
                            </div>

                            <div>
                                <x-breeze.input-label for="level" :value="__('Level')" />
                                <x-breeze.text-input id="level" name="level" type="text" class="mt-1 block w-full" :value="old('level', $course->level)" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('level')" />
                            </div>

                            <div>
                                <x-breeze.input-label for="schedule" :value="__('Schedule')" />
                                <x-breeze.text-input id="schedule" name="schedule" type="text" class="mt-1 block w-full" :value="old('schedule', $course->schedule)" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('schedule')" />
                            </div>

                            @if ( Auth::user()->is_admin )
                                <div>
                                    <x-breeze.input-label for="teacher_id" :value="__('Teacher')" />
                                    <select class="h-8 mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="teacher_id" name="teacher_id">
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $course->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-breeze.input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                                </div>
                            @endif

                            <div class="flex items-center gap-4">
                                <x-breeze.primary-button>{{ __('Save') }}</x-breeze.primary-button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
