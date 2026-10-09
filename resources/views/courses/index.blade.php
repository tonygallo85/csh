<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="px-8 py-2 text-left">Language</th>
                                <th class="px-8 py-2 text-center">Level</th>
                                <th class="px-4 py-2 text-left">Schedule</th>
                                <th class="px-4 py-2 text-left">Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr class="border-b">
                                    <td class="px-8 text-left">{{ $course->language }}</td>
                                    <td class="px-8 text-center">{{ $course->level }}</td>
                                    <td class="px-4 text-left">{{ $course->schedule }}</td>
                                    <td class="px-4 text-left">{{ $course->teacher->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>