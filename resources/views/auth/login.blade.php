<x-guest-layout>
    <div class="text-center space-y-2">
        <h1 class="font-serif text-3xl font-bold text-[#1A1A1A] tracking-normal">Lumière Dining</h1>
        <p class="text-base font-bold text-[#1A1A1A]">Selamat datang kembali</p>
        <p class="text-xs text-[#7A6A58]">Masuk untuk melanjutkan reservasi Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <!-- Email or No HP -->
        <div class="space-y-1.5 w-full">
            <label for="login" class="block text-xs font-semibold text-[#4A3728]">
                Email / No. HP
            </label>
            <input 
                type="text" 
                name="login" 
                id="login"
                value="{{ old('login') }}"
                placeholder="Masukkan email atau no hp"
                required
                autofocus
                class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E8E0D5] rounded-lg font-body text-sm text-[#1A1A1A] placeholder-[#AB9BB0] transition-all duration-200 focus:outline-none focus:border-[#C8882A] focus:ring-2 focus:ring-[#C8882A]/10"
            >
            @error('login')
                <p class="text-xs font-semibold text-[#E95252] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5 w-full" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-semibold text-[#4A3728]">
                    Password
                </label>
                <a href="#" class="text-xs font-semibold text-[#C8882A] hover:underline">Lupa password?</a>
            </div>
            
            <div class="relative">
                <input 
                    :type="show ? 'text' : 'password'" 
                    name="password" 
                    id="password"
                    placeholder="Masukkan password"
                    required
                    class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E8E0D5] rounded-lg font-body text-sm text-[#1A1A1A] placeholder-[#AB9BB0] transition-all duration-200 focus:outline-none focus:border-[#C8882A] focus:ring-2 focus:ring-[#C8882A]/10"
                >
                <button 
                    type="button" 
                    @click="show = !show" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-[#7A6A58] hover:text-[#C8882A] transition-colors"
                >
                    <!-- Eye Icon (hidden password) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Icon (visible password) -->
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs font-semibold text-[#E95252] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="w-full bg-[#C8882A] hover:bg-[#B67720] active:bg-[#9E6517] text-white py-3 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm">
                Masuk
            </button>
        </div>
    </form>

    <div class="relative flex items-center justify-center my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-[#E8E0D5]"></div>
        </div>
        <span class="relative bg-[#FAF7F2] px-3 text-xs font-semibold text-[#7A6A58]">atau</span>
    </div>

    <div class="text-center text-xs text-[#7A6A58]">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-body font-bold text-[#C8882A] hover:underline">Daftar sekarang</a>
    </div>
</x-guest-layout>
