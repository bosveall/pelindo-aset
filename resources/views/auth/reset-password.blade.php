@extends('layouts.app')

@section('title', 'Reset Password')

@section('auth-content')
    <div class="auth-maritime-page min-h-screen bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-xl items-center justify-center">
            <div
                class="w-full rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] sm:p-8">
                <div class="mb-8 flex flex-col items-center text-center">
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

                <div class="mb-6">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Reset Password</h2>
                    <p class="mt-2 text-sm text-slate-500">Masukkan password baru Anda.</p>
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

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password Baru</label>
                        <input type="password" id="password" name="password" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Konfirmasi
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100">
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Simpan Password Baru
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-slate-600">
                    <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:text-sky-700">Kembali ke login</a>
                </div>
            </div>
        </div>
    </div>
@endsection