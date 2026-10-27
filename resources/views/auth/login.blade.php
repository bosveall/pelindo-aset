@extends('layouts.app')

@section('title', 'Login')

@section('auth-content')
    <div class="auth-maritime-page min-h-screen bg-gradient-to-br from-[#eaf7f4] via-white to-[#e8f3fb] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-5xl items-center justify-center">
            <div
                class="grid w-full overflow-hidden rounded-[28px] border border-white/80 bg-white/95 shadow-[0_28px_80px_-30px_rgba(16,42,67,0.35)] backdrop-blur lg:grid-cols-[1.05fr_1.4fr]">
                <div
                    class="relative hidden overflow-hidden bg-gradient-to-br from-[#102a43] via-[#145b67] to-[#0f9f91] p-8 text-white lg:flex lg:flex-col lg:justify-between">
                    <img src="{{ asset('assets/images/harbor.svg') }}" alt="Pelabuhan Pelindo"
                        class="absolute inset-0 h-full w-full object-cover opacity-25 mix-blend-screen">
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="flex items-center justify-center gap-3">
                            <span class="h-14 w-14 shrink-0">
                                <img src="{{ asset('assets/icons/pelindo-logo.png') }}" alt="Logo Pelindo"
                                    class="h-full w-full object-contain">
                            </span>
                            <div class="flex flex-col items-start gap-1">
                                <p class="text-3xl font-bold uppercase leading-none tracking-[0.12em] text-[#58b5e0]">Pelindo</p>
                                <h1 class="text-sm font-medium uppercase leading-none tracking-[0.2em] text-slate-200">Regional 1 Dumai</h1>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10">
                        <p class="mb-3 text-xs font-medium uppercase tracking-[0.28em] text-slate-300">Selamat datang</p>
                        <h2 class="max-w-xs text-3xl font-semibold leading-tight">Kelola aset dengan lebih rapi dan terukur.
                        </h2>
                    </div>

                    <div class="relative z-10 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/15 text-sky-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">Monitoring aset</p>
                                <p class="text-xs text-slate-300">Data lebih cepat dan lebih teratur</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 sm:p-8 lg:p-10">
                    <div class="mx-auto max-w-md">
                        <div class="mb-8 flex flex-col items-center gap-4">
                            <div class="flex flex-col items-center text-center lg:hidden">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="h-10 w-10 shrink-0">
                                        <img src="{{ asset('assets/icons/pelindo-logo.png') }}" alt="Logo Pelindo"
                                            class="h-full w-full object-contain">
                                    </span>
                                    <div class="flex flex-col items-start gap-1">
                                        <p class="text-xl font-bold uppercase leading-none tracking-[0.12em] text-[#006bb3]">Pelindo</p>
                                        <h1 class="text-[10px] font-medium uppercase leading-none tracking-[0.2em] text-slate-500">Regional 1 Dumai</h1>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-slate-600">
                                Login
                            </span>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-3xl font-bold tracking-tight text-slate-900">Masuk</h3>
                            <p class="mt-2 text-sm text-slate-500">Silakan masuk untuk melanjutkan ke dashboard.</p>
                        </div>

                        @if($errors->any())
                            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                                </div>
                                <input type="password" id="password" name="password" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <label class="flex items-center gap-2 text-slate-600">
                                    <input type="checkbox" name="remember"
                                        class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                    Ingat saya
                                </label>
                                <a href="{{ route('password.request') }}"
                                    class="font-medium text-teal-700 hover:text-teal-800">Lupa password?</a>
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-[#102a43] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-[#102a43]/15 transition hover:-translate-y-0.5 hover:bg-[#183f5c]">
                                Masuk ke Dashboard
                            </button>
                        </form>

                        <div class="mt-8 border-t border-slate-200 pt-4 text-center text-xs text-slate-500">
                            © 2026 Pelindo Regional 1 Dumai
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection