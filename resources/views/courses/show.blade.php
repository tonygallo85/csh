<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                @can('update', $course)
                    <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                    ">Edit Course</a>
                @endcan
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="font-semibold text-gray-800 leading-tight">{{ $course->language }} {{ $course->level }}</p>
                    <p>Schedule: {{ $course->schedule }}</p>
                    <p>Teacher: {{ $course->teacher->name }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>