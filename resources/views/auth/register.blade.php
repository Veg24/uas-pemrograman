<x-guest-layout>
    <div x-data="{
        step: {{ $errors->hasAny(['email', 'password']) ? 1 : ($errors->hasAny(['nama', 'no_hp', 'terms']) ? 2 : 1) }},
        submitForm() {
            this.step = 3;
            setTimeout(() => {
                this.$refs.regForm.submit();
            }, 1000);
        }
    }" class="space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="font-serif text-3xl font-black tracking-widest text-[#1A1A1A]">LUMIÈRE</h1>
            <p class="text-xs uppercase tracking-widest text-[#C8882A] font-semibold">Buat Akun Baru</p>
        </div>

        <!-- Stepper Indicator -->
        <div class="flex items-center justify-between max-w-xs mx-auto text-xs font-semibold font-body text-[#7A6A58] py-4">
            <div class="flex flex-col items-center space-y-1">
                <span class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                      :class="step >= 1 ? 'bg-[#C8882A] text-white' : 'bg-[#E8E0D5] text-[#7A6A58]'">1</span>
                <span :class="step >= 1 ? 'text-[#C8882A]' : 'text-[#7A6A58]'">Akun</span>
            </div>
            <div class="flex-1 h-0.5 bg-[#E8E0D5] mx-2 -mt-5" :class="step >= 2 ? 'bg-[#C8882A]' : 'bg-[#E8E0D5]'"></div>
            <div class="flex flex-col items-center space-y-1">
                <span class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                      :class="step >= 2 ? 'bg-[#C8882A] text-white' : 'bg-[#E8E0D5] text-[#7A6A58]'">2</span>
                <span :class="step >= 2 ? 'text-[#C8882A]' : 'text-[#7A6A58]'">Profil</span>
            </div>
            <div class="flex-1 h-0.5 bg-[#E8E0D5] mx-2 -mt-5" :class="step >= 3 ? 'bg-[#C8882A]' : 'bg-[#E8E0D5]'"></div>
            <div class="flex flex-col items-center space-y-1">
                <span class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                      :class="step >= 3 ? 'bg-[#C8882A] text-white' : 'bg-[#E8E0D5] text-[#7A6A58]'">3</span>
                <span :class="step >= 3 ? 'text-[#C8882A]' : 'text-[#7A6A58]'">Selesai</span>
            </div>
        </div>

        <!-- Card Container -->
        <x-card class="shadow-md">
            <form method="POST" action="{{ route('register') }}" x-ref="regForm" @submit.prevent="submitForm">
                @csrf

                <!-- STEP 1: Akun -->
                <div x-show="step === 1" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <x-input 
                        label="Alamat Email" 
                        name="email" 
                        type="email" 
                        placeholder="nama@email.com" 
                        required 
                    />

                    <x-input 
                        label="Kata Sandi" 
                        name="password" 
                        type="password" 
                        placeholder="Minimal 8 karakter" 
                        required 
                    />

                    <x-input 
                        label="Konfirmasi Kata Sandi" 
                        name="password_confirmation" 
                        type="password" 
                        placeholder="Ketik ulang kata sandi" 
                        required 
                    />

                    <div class="pt-4">
                        <x-button type="button" @click="step = 2" variant="primary" class="w-full">
                            Selanjutnya
                        </x-button>
                    </div>
                </div>

                <!-- STEP 2: Profil -->
                <div x-show="step === 2" class="space-y-4" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <x-input 
                        label="Nama Lengkap" 
                        name="nama" 
                        placeholder="Masukkan nama lengkap Anda" 
                        required 
                    />

                    <x-input 
                        label="Nomor Handphone" 
                        name="no_hp" 
                        placeholder="Contoh: 08123456789" 
                        required 
                    />

                    <div class="flex items-start space-x-2 pt-2">
                        <input type="checkbox" name="terms" id="terms" required class="mt-1 rounded border-[#D4C9BB] text-[#C8882A] focus:ring-[#C8882A]/30">
                        <label for="terms" class="text-xs text-[#7A6A58] font-body leading-relaxed">
                            Saya menyetujui <a href="#" class="text-[#C8882A] font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#C8882A] font-semibold hover:underline">Kebijakan Privasi</a> Lumière Dining.
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-xs text-[#E95252] font-semibold mt-1">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center space-x-3 pt-4">
                        <x-button type="button" @click="step = 1" variant="secondary" class="w-1/2">
                            Kembali
                        </x-button>
                        <x-button type="submit" variant="primary" class="w-1/2">
                            Daftar Sekarang
                        </x-button>
                    </div>
                </div>

                <!-- STEP 3: Selesai -->
                <div x-show="step === 3" class="text-center py-8 space-y-4" style="display: none;" x-transition:enter="transition ease-out duration-200">
                    <div class="inline-flex items-center justify-center p-3 bg-[#EAF7F1] rounded-full text-[#4CAF82] animate-bounce">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#1A1A1A]">Pendaftaran Diproses</h3>
                    <p class="text-xs font-body text-[#7A6A58] max-w-xs mx-auto">Sistem sedang membuat akun Anda dan mengautentikasi sesi baru. Mohon tunggu sebentar...</p>
                    <div class="w-8 h-8 border-4 border-[#C8882A] border-t-transparent rounded-full animate-spin mx-auto mt-4"></div>
                </div>
            </form>
        </x-card>

        <div x-show="step < 3" class="text-center text-xs text-[#7A6A58]">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-body font-bold text-[#C8882A] hover:underline">Masuk di sini</a>
        </div>

    </div>
</x-guest-layout>
