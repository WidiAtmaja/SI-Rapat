<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex flex-col md:flex-row w-full h-screen">
        <!-- LEFT SIDE (FORM) -->
        <div class="flex flex-col justify-center items-center w-full md:w-1/2 bg-white px-6 sm:px-10 h-screen md:h-auto">
            <div class="w-full max-w-md">

                <!-- LOGO -->
                <div class="flex justify-center mb-6 space-x-4">
                    <img src="{{ asset('storage/images/logo-kominfo.png') }}" alt="Kominfo" class="w-24 h-24">
                    <img src="{{ asset('storage/images/logo-undiksha.png') }}" alt="BPS" class="w-24 h-24">
                </div>

                <!-- WELCOME TEXT -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Hai, Selamat Datang!</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Masukan akun anda dengan benar untuk lanjut ke website <b>SIRAPAT</b>
                    </p>
                </div>

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full placeholder-gray-400" type="email"
                            name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="Masukan Email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full placeholder-gray-400" type="password"
                            name="password" required autocomplete="current-password" placeholder="Masukkan Password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4 mb-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex flex-col space-y-3">
                        <button type="submit"
                            class="bg-blue-700 hover:bg-blue-500 text-white font-semibold py-2 rounded-md transition">
                            Login
                        </button>
                    </div>

                    <!-- FORGOT PASSWORD -->
                    <div class="mt-4 flex items-center justify-center">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                                <i class="fas fa-lock mr-1"></i> Lupa password?
                            </a>
                        @endif
                    </div>
                </form>

                <!-- FOOTER -->
                <p class="mt-8 text-center text-xs text-gray-400">© 2025 SIRAPAT</p>
            </div>
        </div>

        <!-- RIGHT SIDE (INFO PANEL) -->
        <div
            class="hidden md:flex flex-col justify-center items-center w-full md:w-1/2 bg-blue-700 text-white px-10 py-12">
            <h1 class="text-4xl font-extrabold mb-3 tracking-wide">SIRAPAT</h1>
            <p class="text-lg font-semibold mb-2">(Sistem Informasi Rapat Online)</p>
            <p class="text-center max-w-md text-sm leading-relaxed">
                SIRAPAT merupakan sebuah website yang mempermudah pegawai dalam mengelola rapat, absensi, dan notulen
                dalam satu sistem terpadu.
            </p>
        </div>
    </div>
</x-guest-layout>
