<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if (session('success'))
                this.trigger('success', @js(session('success')));
            @endif
            @if ($errors->any())
                this.trigger('error', @js($errors->first()));
            @endif
        },
        trigger(type, message) {
            this.type = type;
            this.message = message;
            this.show = true;
            setTimeout(() => this.show = false, 4000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    class="fixed top-4 right-4 left-4 sm:left-auto z-[60] max-w-sm"
>
    <div
        class="flex items-start gap-3 p-4 rounded-xl shadow-lg border"
        :class="type === 'success'
            ? 'bg-white dark:bg-gray-900 border-green-100 dark:border-green-500/20'
            : 'bg-white dark:bg-gray-900 border-red-100 dark:border-red-500/20'"
    >
        <div
            class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
            :class="type === 'success' ? 'bg-green-50 dark:bg-green-500/10' : 'bg-red-50 dark:bg-red-500/10'"
        >
            <template x-if="type === 'success'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-green-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </template>
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-200 pt-1" x-text="message"></p>
        <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500 dark:hover:text-gray-400 shrink-0">
            <x-icon name="x" class="w-4 h-4" />
        </button>
    </div>
</div>