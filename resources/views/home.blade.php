<x-guest-layout>
    <h1 class="text-center text-xl font-bold mb-4">Available Courses</h1>

    <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="px-2 py-2 text-left">Language</th>
                    <th class="px-2 py-2 text-center">Level</th>
                    <th class="px-2 py-2 text-left">Schedule</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr class="border-b">
                        <td class="px-2 text-left">{{ $course->language }}</td>
                        <td class="px-2 text-center">{{ $course->level }}</td>
                        <td class="px-2 text-left">{{ $course->schedule }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    <div class="mt-4 flex justify-center gap-4">
        @auth
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
            ">Go to dashboard</a>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                ">Login</a>
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                ">Register</a>    
        @endguest
    </div>

</x-guest-layout>