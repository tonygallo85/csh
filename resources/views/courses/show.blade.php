<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end gap-4">
                @can('update', $course)
                    <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                    ">Edit Course</a>
                @endcan

                @can('delete', $course)
                    <form method="POST" action="{{ route('courses.destroy', $course) }}">
                        @csrf
                        @method('DELETE')

                        <x-breeze.danger-button>Delete</x-breeze.danger-button>
                    </form>
                @endcan
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="font-semibold text-gray-800 leading-tight">{{ $course->language }} {{ $course->level }}</p>
                    <p>Schedule: {{ $course->schedule }}</p>
                    <p>Teacher: {{ $course->teacher->name }}</p>
                    @can('enroll', $course)
                        <form class="mt-4" method="POST" action="{{ route('courses.enrollments.store', $course) }}">
                            @csrf

                            <x-breeze.primary-button>Enroll</x-breeze.primary-button>
                        </form>
                    @endcan
                    @if ($enrollment)
                        <form class="mt-4" method="POST" action="{{ route('courses.enrollments.destroy', [$course, $enrollment]) }}">
                            @csrf
                            @method('DELETE')

                            <x-breeze.danger-button>Unenroll</x-breeze.danger-button>
                        </form>
                    @endif
                </div>
            </div>
                    @can('viewStudents', $course)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="font-semibold text-gray-800 leading-tight mb-4">Enrolled students</p>

                        @if ($course->enrollments->isEmpty())
                            <p>No students are enrolled in this course yet.</p>
                        @else
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="px-4 py-2 text-left">Name</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                        <th class="px-4 py-2 text-left"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($course->enrollments as $studentEnrollment)
                                        <tr class="border-b">
                                            <td class="px-4 py-2 text-left">{{ $studentEnrollment->student->name }}</td>
                                            <td class="px-4 py-2 text-left">{{ $studentEnrollment->student->email }}</td>
                                            <td class="px-4 py-2 text-left">
                                                @if (Auth::user()->is_admin)
                                                    <form method="POST" action="{{ route('courses.enrollments.destroy', [$course, $studentEnrollment]) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <x-breeze.danger-button>Remove</x-breeze.danger-button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endcan

        </div>
    </div>
</x-app-layout>