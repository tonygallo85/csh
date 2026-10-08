<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>    
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PATCH')

                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">

                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror

                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">

                            @error('email')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror

                            <label>
                                <input type="checkbox" name="is_teacher" value="1" @checked($user->is_teacher)>
                                Is Teacher
                            </label>

                            <button type="submit">Save</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
