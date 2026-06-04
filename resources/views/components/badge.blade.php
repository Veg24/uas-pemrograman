@props([
    'variant' => 'neutral'
])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold font-body tracking-wide';
    
    $variants = [
        'success' => 'bg-[#EAF7F1] text-[#4CAF82] border border-[#d1ecd9]',
        'error' => 'bg-[#FDF1F1] text-[#E95252] border border-[#fbd8d8]',
        'warning' => 'bg-[#FEF6EB] text-[#F5A623] border border-[#fce4c4]',
        'info' => 'bg-[#ECF3FE] text-[#3B82F6] border border-[#d2e3fc]',
        'neutral' => 'bg-[#F5F1EB] text-[#7A6A58] border border-[#E8E0D5]',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
