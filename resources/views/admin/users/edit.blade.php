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
                    <form method="POST" class="space-y-6" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-breeze.input-label for="name" :value="__('Name')" />
                                <x-breeze.text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-breeze.input-label for="email" :value="__('Email')" />
                                <x-breeze.text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                                <x-breeze.input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <label for="is_teacher" class="inline-flex items-center" >
                                <input id="is_teacher" type="checkbox" class="rounded border-gray-300" name="is_teacher" value="1" @checked($user->is_teacher)>
                                <span class="ms-2 text-sm text-gray-600">Is Teacher</span>
                                </label>
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
