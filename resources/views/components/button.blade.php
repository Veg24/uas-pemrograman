@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-body font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-[#C8882A] text-white hover:bg-[#4A3728] focus:ring-[#C8882A] active:bg-[#34261C]',
        'secondary' => 'bg-[#F5F1EB] text-[#1A1A1A] border border-[#D4C9BB] hover:bg-[#E8E0D5] focus:ring-[#AB9BB0]',
        'ghost' => 'bg-transparent text-[#1A1A1A] hover:bg-[#F5F1EB] focus:ring-[#D4C9BB]',
        'danger' => 'bg-[#E95252] text-white hover:bg-[#c93f3f] focus:ring-[#E95252]',
    ];

    $sizes = [
        'sm' => 'text-xs px-3 py-1.5 gap-1.5',
        'md' => 'text-sm px-4 py-2 gap-2',
        'lg' => 'text-base px-5 py-2.5 gap-2.5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }} @if($loading) disabled @endif>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
