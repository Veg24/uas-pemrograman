<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success' 
    }"
    x-init="
        @if (session('success'))
            message = '{{ session('success') }}';
            type = 'success';
            show = true;
            setTimeout(() => show = false, 5000);
        @endif
        @if (session('error'))
            message = '{{ session('error') }}';
            type = 'error';
            show = true;
            setTimeout(() => show = false, 5000);
        @endif
        @if (session('warning'))
            message = '{{ session('warning') }}';
            type = 'warning';
            show = true;
            setTimeout(() => show = false, 5000);
        @endif
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-5 right-5 z-50 max-w-md w-full bg-white rounded-2xl shadow-xl border border-[#E8E0D5] p-4 flex items-start space-x-3 pointer-events-auto"
    style="display: none;"
>
    <!-- Icon -->
    <div class="flex-shrink-0">
        <template x-if="type === 'success'">
            <div class="p-1 bg-[#EAF7F1] rounded-lg text-[#4CAF82]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </template>
        <template x-if="type === 'error'">
            <div class="p-1 bg-[#FDF1F1] rounded-lg text-[#E95252]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </template>
        <template x-if="type === 'warning'">
            <div class="p-1 bg-[#FEF6EB] rounded-lg text-[#F5A623]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </template>
    </div>

    <!-- Content -->
    <div class="flex-1 pt-0.5">
        <p class="text-sm font-semibold font-body text-[#1A1A1A]" x-text="type === 'success' ? 'Berhasil' : (type === 'error' ? 'Kesalahan' : 'Peringatan')"></p>
        <p class="text-xs font-body text-[#7A6A58] mt-0.5" x-text="message"></p>
    </div>

    <!-- Close Button -->
    <div class="flex-shrink-0 flex">
        <button @click="show = false" class="text-[#AB9BB0] hover:text-[#7A6A58] transition-colors duration-150">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
