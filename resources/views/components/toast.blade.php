@props(['type' => 'success', 'message'])

<div x-data="{ show: false }"
     x-init="setTimeout(() => show = true, 100)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     @auth-toast.window="show = $event.detail.show; setTimeout(() => show = false, 3000)"
     class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg {{ $type === 'success' ? 'bg-green-500' : 'bg-red-500' }} text-white font-medium"
     style="display: none;">
    {{ $message }}
</div>