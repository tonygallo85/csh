<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="px-8 py-2 text-left">Name</th>
                                <th class="px-8 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-center">Is Teacher</th>
                                <th class="px-4 py-2 text-center">Is Admin</th>
                                <th class="px-4 py-2 text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b">
                                    <td class="px-8 text-left">{{ $user->name }}</td>
                                    <td class="px-8 text-left">{{ $user->email }}</td>
                                    <td class="text-center">
                                        @if ( $user->is_teacher)
                                            ✓
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ( $user->is_admin )
                                            ✓
                                        @endif
                                    </td>
                                    <td class="px-8 text-left"><a href="{{ route('admin.users.edit', $user) }}">Edit</a>
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
