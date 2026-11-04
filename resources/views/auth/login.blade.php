@extends('auth.layout')

@section('title', 'Log in')
@section('heading', 'Log in')
@section('subheading', 'Welcome back. Pick up where you left off.')

@section('content')
    <form method="POST" action="/login" class="flex flex-col gap-5">
        @csrf

        <div class="flex flex-col gap-2">
            <label for="email" class="text-xs font-medium text-ink-400">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                autofocus
                class="input-field rounded-none @error('email') border-red-500/60 @enderror"
            >
            @error('email')
                <p class="text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="password" class="text-xs font-medium text-ink-400">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
                class="input-field rounded-none"
            >
            @error('password')
                <p class="text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <label for="remember" class="flex items-center gap-2.5 text-sm text-ink-400 cursor-pointer select-none">
            <input id="remember" type="checkbox" name="remember" value="1" class="w-4 h-4 accent-accent">
            Keep me logged in
        </label>

        <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-accent hover:bg-accent-hover transition-colors">
            Log in
        </button>
    </form>
@endsection

@section('footer')
    New to Cove? <a href="{{ route('register') }}" class="text-ink-100 font-medium hover:text-amber-400 transition-colors">Create an account</a>
@endsection
