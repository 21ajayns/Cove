<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Cove</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon-180.png?v=3">
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#0f1214">
    @include('partials.theme-init')
    @vite(['resources/css/app.css'])
</head>
<body class="bg-ink-950 text-ink-100 antialiased font-sans text-sm">
    <main class="h-full overflow-y-auto">
        <div class="min-h-full flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm flex flex-col gap-8">
            <div class="flex flex-col items-center gap-3">
                <svg viewBox="0 0 64 64" class="w-12 h-12" role="img" aria-label="Cove">
                    <defs>
                        <linearGradient id="coveGradAuth" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" style="stop-color: rgb(var(--logo-a))"/>
                            <stop offset="100%" style="stop-color: rgb(var(--logo-b))"/>
                        </linearGradient>
                    </defs>
                    <path d="M44 12 A23 23 0 1 0 44 52" fill="none" stroke="url(#coveGradAuth)" stroke-width="4" stroke-linecap="round"/>
                    <path d="M24 27 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-a))" stroke-width="3" stroke-linecap="round"/>
                    <path d="M24 37 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-wave2))" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <span
                    class="text-2xl font-bold tracking-[0.3em]"
                    style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, rgb(var(--logo-a)), rgb(var(--logo-b))); -webkit-background-clip: text; background-clip: text; color: transparent;"
                >COVE</span>
            </div>

            <section class="bg-surface border border-fg/[0.07] shadow-[inset_0_2px_0_0_rgb(var(--accent)/0.8)] p-7 flex flex-col gap-6">
                <div class="flex flex-col gap-1">
                    <h1 class="text-lg font-semibold text-fg">@yield('heading')</h1>
                    <p class="text-sm text-ink-500">@yield('subheading')</p>
                </div>

                @yield('content')
            </section>

            <p class="text-center text-sm text-ink-500">@yield('footer')</p>
        </div>
        </div>
    </main>
</body>
</html>
