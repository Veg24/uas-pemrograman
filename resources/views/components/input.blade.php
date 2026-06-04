@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false
])

<div class="space-y-1.5 w-full">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-semibold font-body text-[#4A3728]">
            {{ $label }} @if ($required)<span class="text-[#E95252]">*</span>@endif
        </label>
    @endif
    
    <div class="relative">
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-2.5 bg-white border rounded-xl font-body text-[#1A1A1A] placeholder-[#AB9BB0] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 ' . 
                ($errors->has($name) 
                    ? 'border-[#E95252] focus:border-[#E95252] focus:ring-[#E95252]/20' 
                    : 'border-[#D4C9BB] focus:border-[#C8882A] focus:ring-[#C8882A]/20')
            ]) }}
        >
        
        {{ $slot }}
    </div>

    @error($name)
        <p class="text-xs font-semibold font-body text-[#E95252] mt-1">{{ $message }}</p>
    @enderror
</div>
