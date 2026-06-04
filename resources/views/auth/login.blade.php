<x-guest-layout>
    <div class="text-center md:text-left space-y-2">
        <h1 class="font-serif text-3xl font-black tracking-widest text-[#1A1A1A]">LUMIÈRE</h1>
        <p class="text-xs uppercase tracking-widest text-[#C8882A] font-semibold">Selamat Datang Kembali</p>
        <p class="text-sm text-[#7A6A58]">Silakan masuk untuk melanjutkan reservasi dan pemesanan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <!-- Email or No HP -->
        <x-input 
            label="Email atau No. Handphone" 
            name="login" 
            placeholder="contoh@email.com / 081234..." 
            required 
            autofocus 
        />

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input 
                label="Kata Sandi" 
                name="password" 
                ::type="show ? 'text' : 'password'" 
                placeholder="Masukkan kata sandi Anda" 
                required
            >
                <button 
                    type="button" 
                    @click="show = !show" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-[#7A6A58] hover:text-[#C8882A] transition-colors"
                >
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </x-input>
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="inline-flex items-center text-[#7A6A58] cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-[#D4C9BB] text-[#C8882A] focus:ring-[#C8882A]/30">
                <span class="ml-2 font-body font-semibold">Ingat Saya</span>
            </label>

            <a href="#" class="font-body font-semibold text-[#C8882A] hover:text-[#4A3728] transition-colors">Lupa Kata Sandi?</a>
        </div>

        <div>
            <x-button type="submit" variant="primary" size="lg" class="w-full">
                Masuk
            </x-button>
        </div>
    </form>

    <div class="mt-8 text-center text-xs text-[#7A6A58]">
        Belum memiliki akun? 
        <a href="{{ route('register') }}" class="font-body font-bold text-[#C8882A] hover:underline">Daftar sekarang</a>
    </div>
</x-guest-layout>
