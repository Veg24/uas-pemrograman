@props([
    'header' => null
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-[#E8E0D5] shadow-sm overflow-hidden']) }}>
    @if ($header || isset($headerSlot))
        <div class="px-6 py-4 border-b border-[#E8E0D5] bg-[#FAF7F2]">
            @if ($header)
                <h3 class="text-lg font-bold font-serif text-[#1A1A1A]">{{ $header }}</h3>
            @else
                {{ $headerSlot }}
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
