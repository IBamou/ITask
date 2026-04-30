<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Change Password') }}</h3>
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 border border-red-200">
                <h3 class="text-lg font-semibold text-red-600 mb-4">{{ __('Delete Account') }}</h3>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>