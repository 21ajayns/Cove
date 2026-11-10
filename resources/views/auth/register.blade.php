@extends('auth.layout')

@section('title', 'Sign up')
@section('heading', 'Create your account')
@section('subheading', 'Your notes, tasks and commands, all in one place.')

@section('content')
    <form method="POST" action="/register" class="flex flex-col gap-5">
        @csrf

        <div class="flex flex-col gap-2">
            <label for="name" class="text-xs font-medium text-ink-400">Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                autocomplete="name"
                required
                autofocus
                class="input-field rounded-none @error('name') border-red-500/60 @enderror"
            >
            @error('name')
                <p class="text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="email" class="text-xs font-medium text-ink-400">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
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
                autocomplete="new-password"
                required
                minlength="8"
                class="input-field rounded-none @error('password') border-red-500/60 @enderror"
            >
            @error('password')
                <p class="text-xs text-red-400">{{ $message }}</p>
            @else
                <p class="text-xs text-ink-500">At least 8 characters.</p>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="password_confirmation" class="text-xs font-medium text-ink-400">Confirm password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                required
                minlength="8"
                class="input-field rounded-none"
            >
        </div>

        <button type="submit" class="w-full py-3 text-sm font-semibold text-on-accent bg-accent hover:bg-accent-hover transition-colors">
            Create account
        </button>
    </form>
@endsection

@section('footer')
    Already have an account? <a href="{{ route('login') }}" class="text-ink-100 font-medium hover:text-amber-400 transition-colors">Log in</a>
@endsection
