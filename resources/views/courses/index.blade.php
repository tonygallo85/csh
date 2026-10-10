<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @can('create', App\Models\Course::class)
                <div class="mb-4 flex justify-end">
                    <a href="{{ route('courses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                        ">+ Create New Course</a>
                    </div>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="px-8 py-2 text-left">Language</th>
                                <th class="px-8 py-2 text-center">Level</th>
                                <th class="px-4 py-2 text-left">Schedule</th>
                                <th class="px-4 py-2 text-left">Teacher</th>
                                <th class="px-4 py-2 text-left"></th>                       
                                <th class="px-4 py-2 text-left"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr class="border-b">
                                    <td class="px-8 text-left">{{ $course->language }}</td>
                                    <td class="px-8 text-center">{{ $course->level }}</td>
                                    <td class="px-4 text-left">{{ $course->schedule }}</td>
                                    <td class="px-4 text-left">{{ $course->teacher->name }}</td>
                                    <td class="px-4 text-left"><a href="{{ route('courses.show', $course) }}">View</a></td>
                                    <td class="px-4 text-left">
                                        @can('update', $course)
                                            <a href="{{ route('courses.edit', $course) }}">Edit</a></td>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>