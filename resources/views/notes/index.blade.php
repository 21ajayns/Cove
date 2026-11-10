<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cove</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon-180.png?v=3">
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#0f1214">
    @include('partials.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink-950 text-ink-100 antialiased font-sans text-sm">

<div
    x-data="notesApp()"
    x-init="init()"
    class="flex h-screen overflow-hidden"
>
    <!-- Splash intro -->
    <div
        x-show="showSplash"
        x-transition:leave="transition-transform duration-700 ease-in-out"
        x-transition:leave-end="-translate-y-full"
        @click="showSplash = false"
        @keydown.window="showSplash = false"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center cursor-pointer select-none"
        style="background: radial-gradient(circle at 50% 42%, var(--splash-1) 0%, var(--splash-2) 65%);"
    >
        <div class="relative w-28 h-28 sm:w-36 sm:h-36 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full animate-[spin_9s_linear_infinite]" style="border:1px solid rgb(var(--logo-a) / 0.18); border-top-color:rgb(var(--logo-a)); border-right-color:rgb(var(--logo-b));"></div>
            <svg viewBox="0 0 64 64" class="w-14 h-14 sm:w-16 sm:h-16" role="img" aria-label="Cove">
                <defs>
                    <linearGradient id="coveGradSplash" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" style="stop-color: rgb(var(--logo-a))"/>
                        <stop offset="100%" style="stop-color: rgb(var(--logo-b))"/>
                    </linearGradient>
                    <filter id="coveGlowSplash" x="-60%" y="-60%" width="220%" height="220%">
                        <feGaussianBlur stdDeviation="2.6" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <path d="M44 12 A23 23 0 1 0 44 52" fill="none" stroke="url(#coveGradSplash)" stroke-width="4" stroke-linecap="round" filter="url(#coveGlowSplash)"/>
                <path d="M24 27 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-a))" stroke-width="3" stroke-linecap="round" filter="url(#coveGlowSplash)"/>
                <path d="M24 37 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-wave2))" stroke-width="3" stroke-linecap="round" filter="url(#coveGlowSplash)"/>
            </svg>
        </div>
        <span
            class="mt-6 text-3xl sm:text-4xl font-bold tracking-[0.3em]"
            style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, rgb(var(--logo-a)), rgb(var(--logo-b))); -webkit-background-clip: text; background-clip: text; color: transparent;"
        >COVE</span>
        <div class="mt-10 flex flex-col items-center gap-2 text-ink-500">
            <span class="text-xs font-semibold uppercase tracking-[0.2em]">Press anywhere to continue</span>
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-bounce">
                <path d="M5 8l5 5 5-5"/>
            </svg>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="w-72 shrink-0 bg-ink-900 border-r border-fg/[0.06] flex flex-col">
        <div class="px-4 pt-4 flex items-center gap-2.5">
            <svg viewBox="0 0 64 64" class="w-8 h-8 shrink-0" role="img" aria-label="Cove">
                <defs>
                    <linearGradient id="coveGradSide" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" style="stop-color: rgb(var(--logo-a))"/>
                        <stop offset="100%" style="stop-color: rgb(var(--logo-b))"/>
                    </linearGradient>
                    <filter id="coveGlowSide" x="-60%" y="-60%" width="220%" height="220%">
                        <feGaussianBlur stdDeviation="1.6" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <path d="M44 12 A23 23 0 1 0 44 52" fill="none" stroke="url(#coveGradSide)" stroke-width="4" stroke-linecap="round" filter="url(#coveGlowSide)"/>
                <path d="M24 27 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-a))" stroke-width="3" stroke-linecap="round" filter="url(#coveGlowSide)"/>
                <path d="M24 37 q4.5 -5 9 0 t9 0" fill="none" style="stroke: rgb(var(--logo-wave2))" stroke-width="3" stroke-linecap="round" filter="url(#coveGlowSide)"/>
            </svg>
            <span
                class="text-sm font-bold tracking-[0.14em]"
                style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, rgb(var(--logo-a)), rgb(var(--logo-b))); -webkit-background-clip: text; background-clip: text; color: transparent;"
            >COVE</span>
        </div>

        <div class="px-4 pt-3">
            <button
                type="button"
                @click="toggleCategory()"
                class="w-full h-12 flex items-center gap-3 pl-[9px] pr-3 rounded-xl bg-fg/[0.04] border border-fg/[0.06] hover:bg-fg/[0.07] text-left transition-colors"
            >
                <span
                    class="w-[30px] h-[30px] shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-accent to-accent-deep text-on-accent text-sm"
                    style="font-family: 'Silkscreen', cursive;"
                    x-text="activeCategory === 'office' ? 'W' : 'P'"
                ></span>
                <span class="flex-1 min-w-0 flex flex-col leading-tight">
                    <span class="text-[13px] font-semibold text-ink-100" x-text="activeCategory === 'office' ? 'Work' : 'Personal'"></span>
                    <span class="text-[10.5px] text-ink-500" x-text="'Switch to ' + (activeCategory === 'office' ? 'Personal' : 'Work')"></span>
                </span>
                <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="text-ink-500 shrink-0"><path d="M4 7h11l-3-3M16 13H5l3 3"/></svg>
            </button>
        </div>

        <nav class="mx-4 mt-3 p-1 grid grid-cols-3 gap-1 rounded-xl tabs-track border border-fg/[0.06]">
            <button
                @click="switchApp('task')"
                class="h-[64px] rounded-[9px] flex flex-col items-center justify-center gap-1.5 text-xs font-medium transition-colors"
                :class="activeApp === 'task' ? 'bg-tab-on text-fg shadow-card [&>svg]:text-tab-icon' : 'text-ink-400 hover:text-ink-100'"
            >
                <svg width="21" height="21" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" class="shrink-0"><rect x="3" y="3" width="14" height="14" rx="3"/><path d="M7 10l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Tasks
            </button>

            <button
                @click="switchApp('command')"
                class="h-[64px] rounded-[9px] flex flex-col items-center justify-center gap-1.5 text-xs font-medium transition-colors"
                :class="activeApp === 'command' ? 'bg-tab-on text-fg shadow-card [&>svg]:text-tab-icon' : 'text-ink-400 hover:text-ink-100'"
            >
                <svg width="21" height="21" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M7 6L3.5 10 7 14M13 6l3.5 4-3.5 4M11.5 4l-3 12"/></svg>
                Comms
            </button>

            <button
                @click="switchApp('notes')"
                class="h-[64px] rounded-[9px] flex flex-col items-center justify-center gap-1.5 text-xs font-medium transition-colors"
                :class="activeApp === 'notes' ? 'bg-tab-on text-fg shadow-card [&>svg]:text-tab-icon' : 'text-ink-400 hover:text-ink-100'"
            >
                <svg width="21" height="21" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" class="shrink-0"><path d="M4 3a1 1 0 011-1h6l4 4v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3z"/><path d="M11 2v4h4"/></svg>
                Notes
            </button>
        </nav>

        <template x-if="activeApp === 'notes'">
            <div class="flex flex-col flex-1 overflow-hidden">
                <div class="px-4 pt-3">
                    <div class="relative">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-500">
                            <circle cx="9" cy="9" r="6.5"/><path d="M18 18l-4-4" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search folders…"
                            class="input-field pl-9"
                        >
                    </div>
                </div>

                <div class="mx-4 mt-3 border-t border-fg/[0.06]"></div>

                <nav class="flex-1 overflow-y-auto py-3">
                    <button
                        @click="selectFolder(null)"
                        class="sidebar-item mx-3 px-2.5 py-2"
                        style="width: calc(100% - 24px)"
                        :class="selectedFolderId === null ? 'sidebar-item-active' : ''"
                    >
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" class="text-ink-500 shrink-0"><path d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0013.414 6L10 2.586A2 2 0 008.586 2H4z"/></svg>
                        All Notes
                    </button>

                    <div class="pt-4 pb-1.5 px-5 text-[10px] font-semibold uppercase tracking-widest text-ink-500">
                        Stacks
                    </div>

                    <div x-html="renderTree(visibleTree())"></div>

                    <p x-show="folderTree.length === 0" class="px-5 text-sm text-ink-500">
                        No folders yet.
                    </p>
                </nav>
            </div>
        </template>

        <template x-if="activeApp === 'command'">
            <div class="flex flex-col flex-1 overflow-hidden">
                <div class="px-4 pt-3">
                    <div class="relative">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-500">
                            <circle cx="9" cy="9" r="6.5"/><path d="M18 18l-4-4" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search folders…"
                            class="input-field pl-9"
                        >
                    </div>
                </div>

                <div class="mx-4 mt-3 border-t border-fg/[0.06]"></div>

                <nav class="flex-1 overflow-y-auto py-3">
                    <button
                        @click="selectFolder(null)"
                        class="sidebar-item mx-3 px-2.5 py-2"
                        style="width: calc(100% - 24px)"
                        :class="selectedFolderId === null ? 'sidebar-item-active' : ''"
                    >
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" class="text-ink-500 shrink-0"><path d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0013.414 6L10 2.586A2 2 0 008.586 2H4z"/></svg>
                        All Comms
                    </button>

                    <div class="pt-4 pb-1.5 px-5 text-[10px] font-semibold uppercase tracking-widest text-ink-500">
                        Stacks
                    </div>

                    <div x-html="renderTree(visibleTree())"></div>

                    <p x-show="folderTree.length === 0" class="px-5 text-sm text-ink-500">
                        No folders yet.
                    </p>
                </nav>
            </div>
        </template>

        {{-- Tasks filter lives at the top of the Tasks page, so the sidebar only keeps the space --}}
        <div x-show="activeApp === 'task'" class="flex-1"></div>

        <div class="mx-4 border-t border-fg/[0.06] py-3 flex items-center gap-2.5">
            <div
                class="w-8 h-8 shrink-0 flex items-center justify-center bg-fg/[0.06] border border-fg/[0.08] text-xs font-semibold text-ink-100 select-none"
                title="{{ auth()->user()->email }}"
            >{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>

            <span class="flex-1 min-w-0 truncate text-[13px] text-ink-400">{{ auth()->user()->name }}</span>

            <button
                type="button"
                onclick="toggleTheme()"
                title="Switch light / dark"
                aria-label="Switch light or dark theme"
                class="w-9 h-9 flex items-center justify-center text-ink-500 hover:bg-fg/[0.06] hover:text-ink-100 transition-colors"
            >
                <svg class="theme-sun" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="10" cy="10" r="3.5"/><path d="M10 2.5v2M10 15.5v2M2.5 10h2M15.5 10h2M4.7 4.7l1.4 1.4M13.9 13.9l1.4 1.4M4.7 15.3l1.4-1.4M13.9 6.1l1.4-1.4"/></svg>
                <svg class="theme-moon" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 11.5A6.5 6.5 0 018.5 3.5a6.5 6.5 0 108 8z"/></svg>
            </button>

            @unless (config('cove.local'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        title="Log out"
                        class="w-9 h-9 flex items-center justify-center text-ink-500 hover:bg-red-500/10 hover:text-red-400 transition-colors"
                    >
                        <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4H4v12h4M13 6l4 4-4 4M17 10H8"/></svg>
                    </button>
                </form>
            @endunless
        </div>
    </aside>

    <!-- Main: Notes -->
    <main x-show="activeApp === 'notes'" x-cloak class="flex-1 flex flex-col overflow-hidden bg-ink-950">
        <header class="border-b border-fg/[0.06] px-8 py-5 flex items-center gap-4">
            <div class="flex items-center gap-2.5 shrink-0" x-show="!selectedNote" x-cloak>
                <button
                    @click="startNewNote()"
                    title="New note"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-on-accent shadow-[0_1px_0_0_rgba(255,255,255,0.16)_inset,0_1px_3px_rgba(0,0,0,0.5)] transition-all duration-150 active:scale-[0.95]"
                    :class="'bg-gradient-to-b from-accent to-accent-deep hover:from-accent-hover hover:to-accent-deep-hover'"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3a1 1 0 00-1 1v16a1 1 0 001 1h12a1 1 0 001-1V8l-5-5H6z"/>
                        <path d="M13 3v5h5"/>
                        <path d="M9.5 14h5M12 11.5v5"/>
                    </svg>
                </button>
                <button
                    @click="openFolderModal()"
                    title="New folder"
                    class="w-11 h-11 rounded-xl flex items-center justify-center bg-fg/[0.04] border border-amber-500/25 text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/40 transition-all duration-150 active:scale-[0.95]"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                        <path d="M12 11v4M10 13h4"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-1.5 text-sm text-ink-500 min-w-0">
                <button @click="selectFolder(null)" class="hover:text-fg transition-colors font-medium shrink-0">All Notes</button>
                <template x-for="(crumb, index) in breadcrumbs" :key="crumb.id">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <button
                            @click="selectFolder(crumb.id)"
                            class="hover:text-fg transition-colors truncate"
                            x-text="crumb.name"
                        ></button>
                    </div>
                </template>
                <template x-if="selectedNote">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <span class="text-fg truncate font-medium" x-text="selectedNote.title || 'New Note'"></span>
                    </div>
                </template>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <template x-if="!selectedNote">
                <div>
                    <p x-show="loading" class="text-sm text-ink-500">Loading…</p>

                    <div x-show="!loading" class="flex flex-col gap-6">
                        <div x-show="subfolders.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="folder in subfolders" :key="folder.id">
                            <div
                                @click="selectFolder(folder.id)"
                                class="folder-block group min-w-0"
                            >
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" class="shrink-0"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span class="truncate" :title="folder.name" x-text="folder.name"></span>

                                <div class="absolute top-1/2 -translate-y-1/2 right-3 flex items-center gap-0.5 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150">
                                    <button
                                        @click.stop="openRenameFolderModal(folder)"
                                        title="Rename folder"
                                        class="folder-block-action"
                                    >
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    </button>
                                    <button
                                        @click.stop="deleteFolder(folder)"
                                        title="Delete folder"
                                        class="folder-block-action"
                                    >
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        </div>

                        <div x-show="notes.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="note in notes" :key="note.id">
                            <div class="note-card min-w-0">
                                <div class="flex items-start gap-3">
                                    <span class="note-card-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 3a1 1 0 00-1 1v16a1 1 0 001 1h12a1 1 0 001-1V8l-5-5H6z"/>
                                            <path d="M13 3v5h5"/>
                                            <path d="M9 13h6M9 17h4"/>
                                        </svg>
                                    </span>

                                    <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative ml-auto">
                                        <button
                                            type="button"
                                            @click="open = !open"
                                            title="More"
                                            aria-label="More actions"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-ink-400 hover:bg-fg/[0.08] hover:text-fg transition-colors"
                                        >
                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><circle cx="4" cy="10" r="1.5"/><circle cx="10" cy="10" r="1.5"/><circle cx="16" cy="10" r="1.5"/></svg>
                                        </button>

                                        <div
                                            x-show="open"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="absolute right-0 top-full mt-1 w-36 rounded-lg bg-surface border border-fg/[0.1] shadow-popover py-1 z-20"
                                        >
                                            <button
                                                type="button"
                                                @click="open = false; editNoteFromList(note)"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-xs text-left text-ink-300 hover:bg-fg/[0.06] transition-colors"
                                            >
                                                <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                                Edit
                                            </button>
                                            <button
                                                type="button"
                                                @click="open = false; deleteNote(note)"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-xs text-left text-red-400 hover:bg-red-500/10 transition-colors"
                                            >
                                                <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/></svg>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="mt-3 font-semibold text-[15px] leading-snug text-fg tracking-tight truncate" :title="note.title" x-text="note.title || 'Untitled'"></h3>
                                <p class="mt-0.5 text-[13px] text-ink-400" x-text="'Created ' + noteAgo(note.created_at)"></p>

                                <div class="mt-4 flex items-center gap-2">
                                    <button type="button" @click="viewNote(note)" class="note-card-btn">Open</button>
                                    <button type="button" @click="editNoteFromList(note)" class="note-card-btn">Edit</button>
                                </div>
                            </div>
                        </template>
                        </div>
                    </div>

                    <div x-show="!loading && notes.length === 0 && subfolders.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-fg/[0.04] border border-fg/[0.06] flex items-center justify-center mb-4">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="text-ink-500"><path d="M4 3a1 1 0 011-1h6l4 4v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3z"/><path d="M11 2v4h4"/></svg>
                        </div>
                        <p class="text-sm text-ink-500">Nothing here yet. Create a note or a folder to get started.</p>
                    </div>
                </div>
            </template>

            <template x-if="selectedNote">
                <div>
                    <!-- View mode: a tinted header band (back, actions, title, chips) above the note. The negative margin cancels the page padding so the band runs edge to edge. -->
                    <template x-if="!isEditingDetail">
                        <div class="-m-8">
                            <section class="px-10 pt-5 pb-7 bg-accent/[0.08] border-b border-accent/[0.18]">
                                <div class="flex items-center gap-2">
                                    <button @click="selectedNote = null; isEditingDetail = false; isNewNote = false;" class="flex items-center gap-1.5 py-1 text-sm text-ink-400 hover:text-fg transition-colors">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                                        Back
                                    </button>
                                    <span class="flex-1"></span>
                                    <button @click="startEditNote()" class="btn-primary !py-2 !px-4">
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                        Edit note
                                    </button>
                                    <button
                                        @click="deleteNote(selectedNote)"
                                        title="Delete note"
                                        aria-label="Delete note"
                                        class="w-[38px] h-[38px] shrink-0 flex items-center justify-center rounded-xl border border-red-400/30 text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors"
                                    >
                                        <svg width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                        </svg>
                                    </button>
                                </div>

                                <p class="mt-5 text-[11px] font-bold uppercase tracking-[0.12em] text-accent">Note</p>
                                <h1 class="mt-1.5 text-[34px] font-bold leading-tight tracking-tight text-fg break-words" x-text="selectedNote.title || 'Untitled'"></h1>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 h-[26px] px-2.5 rounded-full bg-accent/[0.14] text-accent-soft text-xs font-medium max-w-full">
                                        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" class="shrink-0"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                        <span class="truncate" x-text="noteFolderName(selectedNote)"></span>
                                    </span>
                                    <span class="inline-flex items-center h-[26px] px-2.5 rounded-full bg-fg/[0.06] text-ink-400 text-xs" x-text="'Created ' + noteAgo(selectedNote.created_at)"></span>
                                    <span x-show="noteWasEdited(selectedNote)" class="inline-flex items-center h-[26px] px-2.5 rounded-full bg-fg/[0.06] text-ink-400 text-xs" x-text="'Edited ' + noteAgo(selectedNote.updated_at)"></span>
                                </div>
                            </section>

                            <div class="max-w-[760px] px-10 pt-8 pb-12">
                                <div class="note-rich note-view text-ink-300" x-html="noteViewHtml(selectedNote.content)" @click="noteViewClick($event)"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Edit / create mode -->
                    <template x-if="isEditingDetail">
                      <div class="max-w-2xl">
                        <button @click="selectedNote = null; isEditingDetail = false; isNewNote = false;" class="flex items-center gap-1.5 text-sm text-ink-500 hover:text-fg transition-colors mb-6">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                            Back
                        </button>
                        <form @submit.prevent="saveDetail()" class="space-y-5">
                            <div>
                                <input
                                    type="text"
                                    x-model="detailForm.title"
                                    placeholder="Untitled"
                                    class="w-full bg-transparent text-2xl font-bold text-fg tracking-tight placeholder-ink-600 outline-none border-b border-fg/10 focus:border-amber-600/60 pb-2 transition-colors"
                                >
                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.title" x-text="errors.title?.[0]"></p>
                            </div>

                            <div>
                                <div
                                    x-ref="noteEditor"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-multiline="true"
                                    data-placeholder="Write something…"
                                    x-init="noteInitEditor($el)"
                                    @input="noteEditorInput($event)"
                                    @keydown="noteEditorKeydown($event)"
                                    @paste="noteEditorPaste($event)"
                                    class="note-rich note-editor"
                                ></div>

                                <!-- Floating toolbar: appears above selected text -->
                                <div
                                    x-show="noteTool.show"
                                    x-cloak
                                    class="note-toolbar"
                                    :style="`left:${noteTool.x}px; top:${noteTool.y}px`"
                                    @mousedown="if ($event.target.tagName !== 'INPUT') $event.preventDefault()"
                                    @selectionchange.document="noteUpdateToolbar()"
                                    @scroll.capture.window="noteUpdateToolbar()"
                                    @resize.window="noteUpdateToolbar()"
                                >
                                    <template x-if="!noteTool.linkMode">
                                        <div class="flex items-center gap-0.5">
                                            <button type="button" class="note-tool font-mono" :class="noteTool.code && 'is-on'" @click="noteToggleCode()" title="Code: click to copy once saved">&lt;/&gt;</button>
                                            <button type="button" class="note-tool" :class="noteTool.link && 'is-on'" @click="noteOpenLink()" title="Link">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.07 0l3-3a5 5 0 00-7.07-7.07l-1.5 1.5"/><path d="M14 11a5 5 0 00-7.07 0l-3 3a5 5 0 007.07 7.07l1.5-1.5"/></svg>
                                            </button>
                                            <button type="button" class="note-tool" :class="noteTool.red && 'is-on'" @click="noteToggleRed()" title="Red text">
                                                <span class="w-3.5 h-3.5 rounded-full" style="background:#ef4444"></span>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="noteTool.linkMode">
                                        <div class="flex items-center gap-1">
                                            <input
                                                x-ref="noteLinkInput"
                                                x-model="noteTool.url"
                                                @keydown.enter.prevent="noteApplyLink()"
                                                @keydown.escape.prevent="noteCloseLink()"
                                                type="text"
                                                placeholder="Paste a link…"
                                                class="h-7 w-52 bg-transparent text-xs text-fg outline-none px-2 placeholder-ink-600"
                                            >
                                            <button type="button" class="note-tool" @click="noteApplyLink()">Apply</button>
                                            <button type="button" class="note-tool" x-show="noteTool.link" @click="noteRemoveLink()">Remove</button>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.content" x-text="errors.content?.[0]"></p>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                    :class="'from-accent to-accent-deep hover:from-accent-hover hover:to-accent-deep-hover'"
                                >Save</button>
                                <button type="button" @click="cancelEditDetail()" class="btn-ghost">Cancel</button>
                            </div>
                        </form>
                      </div>
                    </template>
                </div>
            </template>
        </div>
    </main>

    <!-- Main: Commands -->
    <main x-show="activeApp === 'command'" x-cloak class="flex-1 flex flex-col overflow-hidden bg-ink-950">
        <header class="border-b border-fg/[0.06] px-8 py-5 flex items-center gap-4">
            <div class="flex items-center gap-2.5 shrink-0" x-show="!selectedCommand" x-cloak>
                <button
                    @click="startNewCommand()"
                    title="New command set"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-on-accent shadow-[0_1px_0_0_rgba(255,255,255,0.16)_inset,0_1px_3px_rgba(0,0,0,0.5)] transition-all duration-150 active:scale-[0.95]"
                    :class="'bg-gradient-to-b from-accent to-accent-deep hover:from-accent-hover hover:to-accent-deep-hover'"
                >
                    <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 6L3.5 10 7 14M13 6l3.5 4-3.5 4M11.5 4l-3 12"/></svg>
                </button>
                <button
                    @click="openFolderModal()"
                    title="New folder"
                    class="w-11 h-11 rounded-xl flex items-center justify-center bg-fg/[0.04] border border-amber-500/25 text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/40 transition-all duration-150 active:scale-[0.95]"
                >
                    <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                        <path d="M12 11v4M10 13h4"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-1.5 text-sm text-ink-500 min-w-0">
                <button @click="selectFolder(null)" class="hover:text-fg transition-colors font-medium shrink-0">All Comms</button>
                <template x-for="(crumb, index) in breadcrumbs" :key="crumb.id">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <button
                            @click="selectFolder(crumb.id)"
                            class="hover:text-fg transition-colors truncate"
                            x-text="crumb.name"
                        ></button>
                    </div>
                </template>
                <template x-if="selectedCommand">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <span class="text-fg truncate font-medium" x-text="selectedCommand.title || 'New Command Set'"></span>
                    </div>
                </template>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <template x-if="!selectedCommand">
                <div>
                    <p x-show="loading" class="text-sm text-ink-500">Loading…</p>

                    <div
                        x-show="!loading && (subfolders.length > 0 || commands.length > 0)"
                        class="max-w-5xl border border-fg/[0.07]"
                    >
                        <div class="cmd-list-row cmd-head">
                            <span></span>
                            <span>Name</span>
                            <span>First command</span>
                            <span class="text-right">Count</span>
                            <span></span>
                        </div>

                        <template x-for="folder in subfolders" :key="folder.id">
                            <div
                                @click="selectFolder(folder.id)"
                                class="cmd-list-row group cursor-pointer"
                                style="--c: rgb(var(--folder-accent) / 0.7)"
                            >
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" class="text-amber-400/80"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span class="text-sm font-semibold text-fg truncate" :title="folder.name" x-text="folder.name"></span>
                                <span class="text-xs text-ink-500">Folder</span>
                                <span></span>
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <button
                                        @click.stop="openRenameFolderModal(folder)"
                                        title="Rename folder"
                                        class="tile-action hover:text-amber-400 hover:bg-amber-500/10"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    </button>
                                    <button
                                        @click.stop="deleteFolder(folder)"
                                        title="Delete folder"
                                        class="tile-action hover:text-red-400 hover:bg-red-500/10"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-for="cmd in commands" :key="cmd.id">
                            <div
                                @click="viewCommand(cmd)"
                                class="cmd-list-row group cursor-pointer"
                                style="--c: rgb(var(--accent) / 0.8)"
                            >
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="text-ink-500"><path d="M4 6l4 4-4 4M10 15h6"/></svg>
                                <span class="text-sm font-semibold text-fg truncate" :title="cmd.title" x-text="cmd.title"></span>
                                <code
                                    class="font-mono text-[12px] truncate"
                                    :class="cmd.rows.length ? 'text-cmd/80' : 'text-ink-500'"
                                    :title="cmd.rows[0]?.value || ''"
                                    x-text="cmd.rows[0]?.value || 'Empty'"
                                ></code>
                                <span class="font-mono text-[11px] text-ink-500 text-right" x-text="cmd.rows.length"></span>
                                <div class="flex items-center justify-end opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <button
                                        @click.stop="deleteCommand(cmd)"
                                        title="Delete command set"
                                        class="tile-action hover:text-red-400 hover:bg-red-500/10"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="!loading && commands.length === 0 && subfolders.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-14 h-14 bg-fg/[0.04] border border-fg/[0.06] flex items-center justify-center mb-4">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="text-ink-500"><path d="M7 6L3.5 10 7 14M13 6l3.5 4-3.5 4M11.5 4l-3 12"/></svg>
                        </div>
                        <p class="text-sm text-ink-500">Nothing here yet. Create a command set or a folder to get started.</p>
                    </div>
                </div>
            </template>

            <template x-if="selectedCommand">
                <div class="max-w-5xl">
                    <button @click="selectedCommand = null; isEditingCommandDetail = false; isNewCommand = false;" class="flex items-center gap-1.5 text-sm text-ink-500 hover:text-fg transition-colors mb-6">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                        Back
                    </button>

                    <!-- View mode -->
                    <template x-if="!isEditingCommandDetail">
                        <div>
                            <h1 class="text-2xl font-bold text-fg tracking-tight mb-5" x-text="selectedCommand.title"></h1>

                            <div class="border border-fg/[0.07]">
                                <div x-show="selectedCommand.rows.length > 0" class="cmd-row cmd-head">
                                    <span>#</span>
                                    <span>What</span>
                                    <span>Command</span>
                                    <span></span>
                                </div>

                                <template x-for="(row, idx) in selectedCommand.rows" :key="idx">
                                    <div x-data="{ copied: false }" class="cmd-row">
                                        <span class="font-mono text-[11px] text-ink-500" x-text="String(idx + 1).padStart(2, '0')"></span>
                                        <span class="text-sm text-fg truncate" :title="row.label" x-text="row.label"></span>
                                        <code class="font-mono text-[12.5px] text-cmd truncate" :title="row.value" x-text="row.value"></code>
                                        <button
                                            type="button"
                                            @click="copyText(row.value).then((ok) => { if (ok) { copied = true; setTimeout(() => copied = false, 1500); } })"
                                            class="border py-1.5 text-[10px] font-semibold uppercase tracking-wider transition-colors"
                                            :class="copied ? 'border-emerald-400/50 text-emerald-400' : 'border-fg/[0.16] text-ink-400 hover:border-accent hover:text-accent hover:bg-accent/10'"
                                            title="Copy to clipboard"
                                            x-text="copied ? 'Copied' : 'Copy'"
                                        ></button>
                                    </div>
                                </template>

                                <p x-show="selectedCommand.rows.length === 0" class="px-5 py-6 text-sm text-ink-500 text-center">No commands in this set yet.</p>
                            </div>

                            <div class="mt-8 flex items-center gap-2">
                                <button @click="startEditCommand()" class="btn-secondary">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    Edit
                                </button>
                                <button @click="deleteCommand(selectedCommand)" class="btn-ghost text-red-400 hover:bg-red-500/10 hover:text-red-300">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Edit / create mode -->
                    <template x-if="isEditingCommandDetail">
                        <form @submit.prevent="saveCommandDetail()" class="space-y-5">
                            <div>
                                <input
                                    type="text"
                                    x-model="commandDetailForm.title"
                                    placeholder="Untitled"
                                    class="w-full bg-transparent text-2xl font-bold text-fg tracking-tight placeholder-ink-600 outline-none border-b border-fg/10 focus:border-amber-600/60 pb-2 transition-colors"
                                >
                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.title" x-text="errors.title?.[0]"></p>
                            </div>

                            <div class="space-y-2.5">
                                <template x-for="(row, idx) in commandDetailForm.rows" :key="idx">
                                    <div class="flex items-center gap-2.5">
                                        <input type="text" x-model="row.label" placeholder="What is it…" class="input-field flex-1">
                                        <input type="text" x-model="row.value" placeholder="Command…" class="input-field flex-1 font-mono text-xs">
                                        <button
                                            type="button"
                                            @click="commandDetailForm.rows.splice(idx, 1)"
                                            title="Remove row"
                                            class="w-9 h-9 rounded-lg flex items-center justify-center text-ink-500 hover:text-red-400 hover:bg-red-500/10 shrink-0"
                                        >
                                            <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 5l10 10M15 5L5 15"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="commandDetailForm.rows.push({ label: '', value: '' })" class="btn-ghost text-xs">
                                    <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 4v12M4 10h12"/></svg>
                                    Add command
                                </button>

                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.rows" x-text="errors.rows?.[0]"></p>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                    :class="'from-accent to-accent-deep hover:from-accent-hover hover:to-accent-deep-hover'"
                                >Save</button>
                                <button type="button" @click="cancelEditCommandDetail()" class="btn-ghost">Cancel</button>
                            </div>
                        </form>
                    </template>
                </div>
            </template>
        </div>
    </main>

    <!-- Main: Tasks -->
    <main
        x-show="activeApp === 'task'"
        x-cloak
        @keydown.window="if (activeApp === 'task' && $event.key.toLowerCase() === 'n' && !$event.ctrlKey && !$event.metaKey && !$event.altKey && !['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName) && !$event.target.isContentEditable) { $event.preventDefault(); $refs.taskInput.focus(); }"
        class="flex-1 flex flex-col overflow-hidden bg-ink-950"
    >
        <header class="border-b border-fg/[0.06] px-8 py-5 flex items-center gap-4">
            <div class="flex items-center gap-1.5 text-sm text-ink-500 min-w-0 flex-1">
                <span class="text-fg font-medium">Tasks</span>
                <span class="text-ink-600">/</span>
                <span x-text="taskStatusFilter ? taskStatusFilter.charAt(0).toUpperCase() + taskStatusFilter.slice(1) : 'All'"></span>
            </div>
            <button
                type="button"
                @click="clearTasks()"
                x-show="tasks.length > 0"
                class="btn-ghost text-red-400 hover:bg-red-500/10 hover:text-red-300 shrink-0"
            >
                <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                </svg>
                Clear all
            </button>
        </header>

        <div class="flex-1 overflow-y-auto px-8 py-6">
            <div class="max-w-3xl">
                <div class="inline-flex flex-wrap gap-0.5 p-[3px] rounded-[10px] tabs-track border border-fg/[0.06]" role="group" aria-label="Filter tasks">
                    <template x-for="filter in [{ key: null, label: 'All' }, ...taskStatuses]" :key="filter.label">
                        <button
                            type="button"
                            @click="filterTasks(filter.key)"
                            class="px-3 py-1.5 rounded-lg text-[12.5px] transition-colors"
                            :class="taskStatusFilter === filter.key ? 'bg-surface text-fg shadow-card' : 'text-ink-500 hover:text-ink-100'"
                        >
                            <span x-text="filter.label"></span>
                            <b class="ml-1 font-semibold tabular-nums" x-text="filter.key ? taskCount(filter.key) : tasks.length"></b>
                        </button>
                    </template>
                </div>

                <form @submit.prevent="addTask()" class="mt-4 flex items-center gap-3 px-3.5 py-3 border border-dashed border-fg/[0.16] rounded-[10px]">
                    <span class="w-5 h-5 shrink-0 rounded-full border-[1.75px] border-dashed border-ink-500 flex items-center justify-center text-ink-500 text-sm leading-none">+</span>
                    <input
                        type="text"
                        x-ref="taskInput"
                        x-model="taskForm.title"
                        placeholder="Add a task"
                        class="flex-1 min-w-0 bg-transparent text-[15px] text-ink-100 placeholder-ink-500 outline-none"
                    >
                    <button
                        type="submit"
                        x-show="taskForm.title.trim() !== ''"
                        class="text-xs font-semibold text-accent hover:text-accent-hover transition-colors"
                    >Add</button>
                    <span x-show="taskForm.title.trim() === ''" title="Press N to add a task"><kbd class="ledger-kbd">N</kbd></span>
                </form>

                <p x-show="taskLoading" class="mt-4 text-sm text-ink-500">Loading…</p>

                <div x-show="!taskLoading && visibleTasks().length > 0" class="mt-6 flex flex-col gap-[18px]">
                    <template x-for="statusGroup in taskGroups()" :key="statusGroup.key">
                    <section
                        x-show="draggingTaskId !== null || taskCount(statusGroup.key) > 0"
                        class="flex flex-col gap-2 -mx-2 px-2 -my-1.5 py-1.5 rounded-xl transition-colors"
                        :class="dragOverKey === statusGroup.key ? 'bg-fg/[0.04]' : ''"
                        :style="dragOverKey === statusGroup.key ? `box-shadow: inset 0 0 0 1.5px ${statusGroup.border}` : ''"
                        @dragover="if (draggingTaskId !== null) { $event.preventDefault(); $event.dataTransfer.dropEffect = 'move'; dragOverKey = statusGroup.key; }"
                        @dragleave="if (!$el.contains($event.relatedTarget) && dragOverKey === statusGroup.key) dragOverKey = null"
                        @drop.prevent="dropTask(statusGroup.key)"
                    >
                        <div class="flex items-center gap-2.5 h-[22px]">
                            <span class="w-[9px] h-[9px] rounded-[2px] shrink-0" :style="`background: ${statusGroup.color}`"></span>
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.1em]" :style="`color: ${statusGroup.color}`" x-text="statusGroup.label"></h3>
                            <span class="font-mono text-[11px] text-ink-500 tabular-nums" x-text="taskCount(statusGroup.key)"></span>
                            <span class="flex-1 h-px bg-fg/[0.07]"></span>
                            <button
                                type="button"
                                x-show="statusGroup.key === 'completed' && taskStatusFilter !== 'completed'"
                                @click="toggleShowCompleted()"
                                :aria-expanded="showCompleted.toString()"
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-xs text-ink-400 hover:text-ink-100 hover:bg-fg/[0.06] transition-colors"
                            >
                                <span x-text="showCompleted ? 'Hide' : 'Show'"></span>
                                <svg width="9" height="9" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-150" :class="showCompleted ? '-rotate-180' : ''"><path d="M5 8l5 5 5-5"/></svg>
                            </button>
                        </div>

                        <div x-show="statusGroup.key !== 'completed' || showCompleted || taskStatusFilter === 'completed'" class="flex flex-col gap-1.5">
                    <template x-for="task in statusGroup.tasks" :key="task.id">
                        <div
                            draggable="true"
                            @dragstart="startTaskDrag($event, task)"
                            @dragend="endTaskDrag()"
                            title="Drag to another status"
                            class="group relative flex items-center gap-3.5 pl-3.5 pr-2.5 py-2.5 bg-surface border border-fg/[0.07] rounded-[9px] text-[15px] cursor-grab active:cursor-grabbing transition-opacity"
                            :class="draggingTaskId === task.id ? 'opacity-40' : ''"
                        >
                            <svg aria-hidden="true" width="8" height="12" viewBox="0 0 8 12" fill="currentColor" class="absolute left-[3px] top-1/2 -translate-y-1/2 text-ink-500 opacity-0 group-hover:opacity-70 transition-opacity"><circle cx="2" cy="2" r="1"/><circle cx="6" cy="2" r="1"/><circle cx="2" cy="6" r="1"/><circle cx="6" cy="6" r="1"/><circle cx="2" cy="10" r="1"/><circle cx="6" cy="10" r="1"/></svg>
                            <button
                                type="button"
                                @click="toggleTaskCompleted(task)"
                                :title="task.status === 'completed' ? 'Mark not done' : 'Mark done'"
                                class="w-5 h-5 shrink-0 rounded-full border-[1.75px] flex items-center justify-center transition-colors text-ink-950"
                                :class="task.status === 'completed' ? 'bg-emerald-400 border-emerald-400' : 'hover:bg-fg/[0.06]'"
                                :style="task.status === 'completed' ? '' : `border-color: ${statusGroup.ring}`"
                            >
                                <svg x-show="task.status === 'completed'" width="11" height="11" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5l4 4 8-9"/></svg>
                            </button>

                            <span
                                class="flex-1 min-w-0 truncate"
                                :class="task.status === 'completed' ? 'line-through text-ink-500' : 'text-fg'"
                                :title="task.title"
                                x-text="task.title"
                            ></span>

                            <div x-data="{ open: false }" @click.outside="open = false" class="relative shrink-0">
                                <button
                                    type="button"
                                    @click="open = !open"
                                    title="Change status"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 -mx-2 rounded-md text-xs font-medium transition-colors hover:bg-fg/[0.06]"
                                    :style="`color: ${taskStatusMeta(task.status).color}`"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" :style="`background: ${taskStatusMeta(task.status).color}`"></span>
                                    <span x-text="taskStatusMeta(task.status).label"></span>
                                    <svg width="9" height="9" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="opacity-60 transition-transform duration-150" :class="open ? '-rotate-180' : ''"><path d="M5 8l5 5 5-5"/></svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute right-0 top-full mt-1 w-40 rounded-lg bg-surface border border-fg/[0.1] shadow-popover py-1 z-20"
                                >
                                    <template x-for="option in taskStatuses" :key="option.key">
                                        <button
                                            type="button"
                                            @click="updateTaskStatus(task, option.key); open = false"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-left transition-colors hover:bg-fg/[0.06]"
                                            :class="task.status === option.key ? 'font-semibold' : 'text-ink-400'"
                                            :style="task.status === option.key ? `color: ${option.color}` : ''"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0" :style="`background: ${option.color}`"></span>
                                            <span x-text="option.label"></span>
                                            <svg x-show="task.status === option.key" width="10" height="10" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="ml-auto"><path d="M4 10l4 4 8-8"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <span
                                class="w-9 text-right font-mono text-[11px] text-ink-500 shrink-0"
                                :title="task.created_at ? 'Added ' + new Date(task.created_at).toLocaleString() : ''"
                                x-text="taskAge(task.created_at)"
                            ></span>

                            <button
                                type="button"
                                @click="deleteTask(task)"
                                title="Delete task"
                                class="w-7 h-7 shrink-0 flex items-center justify-center rounded-md text-ink-500 opacity-0 group-hover:opacity-100 focus-visible:opacity-100 hover:text-red-400 hover:bg-red-500/10 transition-all"
                            >
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/></svg>
                            </button>
                        </div>
                    </template>
                        </div>

                        <div
                            x-show="taskCount(statusGroup.key) === 0"
                            class="h-11 rounded-[9px] border border-dashed border-fg/[0.16] flex items-center justify-center text-xs text-ink-500"
                        >Drop here</div>
                    </section>
                    </template>
                </div>

                <div x-show="!taskLoading && visibleTasks().length === 0" class="py-16 text-center">
                    <p
                        class="text-sm text-ink-500"
                        x-text="taskStatusFilter ? 'No ' + taskStatusMeta(taskStatusFilter).label.toLowerCase() + ' tasks.' : 'No tasks here yet. Add one above to get started.'"
                    ></p>
                </div>
            </div>
        </div>
    </main>

    <!-- New Folder Modal -->
    <div
        x-show="showFolderModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50"
        style="display: none;"
    >
        <div
            @click.outside="showFolderModal = false"
            x-show="showFolderModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="card w-full max-w-md p-7 shadow-popover"
        >
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-fg tracking-tight" x-text="renamingFolderId ? 'Rename Folder' : 'New Folder'"></h2>
                <span
                    class="text-[11px] font-semibold px-2.5 py-1 rounded-full"
                    :class="'bg-accent/10 text-accent-soft'"
                    x-text="activeCategory === 'office' ? '💼 Work' : '🏠 Personal'"
                ></span>
            </div>

            <form @submit.prevent="submitFolder()" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium mb-1.5 text-ink-400">Name</label>
                    <input type="text" x-model="folderForm.name" class="input-field">
                    <p class="text-xs text-red-400 mt-1.5" x-show="errors.name" x-text="errors.name?.[0]"></p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showFolderModal = false" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-secondary" x-text="renamingFolderId ? 'Save' : 'Create'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm dialog. Used instead of the browser's confirm(), which in the desktop app leaves the
         page unable to take keyboard input after it closes. -->
    <div
        x-show="confirmDialog.show"
        x-cloak
        x-transition.opacity
        x-effect="if (confirmDialog.show) $nextTick(() => $refs.confirmOk.focus())"
        @keydown.escape.window="if (confirmDialog.show) answerConfirm(false)"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50"
        style="display: none;"
    >
        <div
            @click.outside="answerConfirm(false)"
            role="alertdialog"
            aria-modal="true"
            class="card w-full max-w-sm p-6 shadow-popover"
        >
            <p class="text-sm text-fg leading-relaxed" x-text="confirmDialog.message"></p>
            <div class="flex justify-end gap-2 pt-5">
                <button type="button" @click="answerConfirm(false)" class="btn-ghost">Cancel</button>
                <button type="button" x-ref="confirmOk" @click="answerConfirm(true)" class="btn-secondary text-red-400" x-text="confirmDialog.okLabel"></button>
            </div>
        </div>
    </div>
</div>

<script>
    // Every API call carries the session's CSRF token; an expired session goes back to the login page.
    (() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const nativeFetch = window.fetch.bind(window);

        window.fetch = async (input, init = {}) => {
            const headers = new Headers(init.headers || {});
            headers.set('X-CSRF-TOKEN', csrfToken);
            headers.set('X-Requested-With', 'XMLHttpRequest');
            if (!headers.has('Accept')) {
                headers.set('Accept', 'application/json');
            }

            const response = await nativeFetch(input, { ...init, headers, credentials: 'same-origin' });

            if (response.status === 401 || response.status === 419) {
                window.location.href = '/login';
            }

            return response;
        };
    })();

    function notesApp() {
        // Resolves the open confirm dialog's promise; kept outside Alpine's reactive state.
        let pendingConfirm = null;
        // Where keyboard focus was before the confirm dialog opened, so it can go back there.
        let focusBeforeConfirm = null;

        return {
            showSplash: true,
            confirmDialog: { show: false, message: '', okLabel: 'Delete' },
            activeApp: 'task',
            activeCategory: localStorage.getItem('activeCategory') || 'office',
            tasks: [],
            taskStatusFilter: null,
            taskForm: { title: '' },
            taskLoading: false,
            taskStatuses: [
                { key: 'critical', label: 'Critical', color: 'rgb(var(--st-critical))', border: 'rgb(var(--st-critical) / 0.35)', ring: 'rgb(var(--st-critical) / 0.7)' },
                { key: 'active', label: 'Active', color: 'rgb(var(--st-active))', border: 'rgb(var(--st-active) / 0.35)', ring: 'rgb(var(--st-active) / 0.7)' },
                { key: 'upcoming', label: 'Upcoming', color: 'rgb(var(--st-upcoming))', border: 'rgb(var(--st-upcoming) / 0.35)', ring: 'rgb(var(--st-upcoming) / 0.7)' },
                { key: 'completed', label: 'Completed', color: 'rgb(var(--st-completed))', border: 'rgb(var(--st-completed) / 0.35)', ring: 'rgb(var(--st-completed) / 0.7)' },
            ],
            draggingTaskId: null,
            dragOverKey: null,
            // Completed tasks are folded away under their heading until shown; remembered per browser.
            showCompleted: (() => { try { return localStorage.getItem('showCompleted') === '1'; } catch (e) { return false; } })(),
            folderTree: [],
            nodesById: {},
            selectedFolderId: null,
            breadcrumbs: [],
            subfolders: [],
            notes: [],
            selectedNote: null,
            noteTool: { show: false, x: 0, y: 0, linkMode: false, url: '', code: false, link: false, red: false },
            noteRange: null,
            isEditingDetail: false,
            isNewNote: false,
            detailForm: { title: '', content: '' },
            commands: [],
            selectedCommand: null,
            isEditingCommandDetail: false,
            isNewCommand: false,
            commandDetailForm: { title: '', rows: [] },
            loading: false,
            search: '',
            showFolderModal: false,
            renamingFolderId: null,
            folderForm: { name: '' },
            errors: {},

            init() {
                // Tasks is the first tab, so the app opens on it; Notes and Comms load when you switch to them.
                this.loadTasks();
            },

            // In-page replacement for confirm(): resolves true if the person confirms.
            askConfirm(message, okLabel = 'Delete') {
                if (pendingConfirm) {
                    pendingConfirm(false);
                }

                focusBeforeConfirm = document.activeElement;
                this.confirmDialog = { show: true, message, okLabel };

                return new Promise((resolve) => { pendingConfirm = resolve; });
            },

            answerConfirm(ok) {
                if (!this.confirmDialog.show) {
                    return;
                }

                this.confirmDialog.show = false;
                const resolve = pendingConfirm;
                pendingConfirm = null;

                const previous = focusBeforeConfirm;
                focusBeforeConfirm = null;
                if (previous && previous.isConnected && typeof previous.focus === 'function') {
                    previous.focus();
                }

                if (resolve) {
                    resolve(ok);
                }
            },

            escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            },

            // Copies text to the clipboard; resolves true only if it worked. Falls back to the old
            // execCommand route where the clipboard API is missing or refused.
            async copyText(text) {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (e) {
                    const field = document.createElement('textarea');
                    field.value = text;
                    field.setAttribute('readonly', '');
                    field.style.cssText = 'position:fixed;top:0;left:0;opacity:0;';
                    document.body.appendChild(field);
                    field.select();
                    let ok = false;
                    try { ok = document.execCommand('copy'); } catch (err) { ok = false; }
                    document.body.removeChild(field);
                    return ok;
                }
            },

            toggleCategory() {
                this.activeCategory = this.activeCategory === 'office' ? 'personal' : 'office';
                localStorage.setItem('activeCategory', this.activeCategory);

                if (this.activeApp === 'task') {
                    this.loadTasks();
                    return;
                }

                this.folderTree = [];
                this.nodesById = {};
                this.loadContent(null);
            },

            switchApp(app) {
                const previousApp = this.activeApp;
                this.activeApp = app;

                if (previousApp === app) {
                    return;
                }

                if (app === 'task') {
                    this.loadTasks();
                    return;
                }

                if (app === 'notes' || app === 'command') {
                    this.selectedNote = null;
                    this.isEditingDetail = false;
                    this.isNewNote = false;
                    this.selectedCommand = null;
                    this.isEditingCommandDetail = false;
                    this.isNewCommand = false;
                    this.folderTree = [];
                    this.nodesById = {};
                    this.loadContent(null);
                }
            },

            folderApiBase() {
                return this.activeApp === 'command' ? '/api/command-folders' : '/api/folders';
            },

            contentApiBase() {
                return this.activeApp === 'command' ? '/api/commands' : '/api/notes';
            },

            async loadTasks() {
                this.taskLoading = true;

                const params = new URLSearchParams({ category: this.activeCategory });

                const res = await fetch(`/api/tasks?${params}`);
                this.tasks = await res.json();
                this.taskLoading = false;
            },

            filterTasks(status) {
                this.taskStatusFilter = status;
            },

            visibleTasks() {
                const order = { critical: 0, active: 1, upcoming: 2, completed: 3 };

                return this.tasks
                    .filter((t) => !this.taskStatusFilter || t.status === this.taskStatusFilter)
                    .sort((a, b) => order[a.status] - order[b.status]);
            },

            // The visible tasks split into one group per status (Critical, Active, Upcoming, Completed).
            // Every group is always returned; the page hides empty ones unless a task is being dragged,
            // so the groups never get added or removed mid-drag (which would cancel it). The page reads
            // counts with taskCount(key), not group.tasks.length: Alpine can evaluate x-show before it
            // refreshes a reused group's data, which left a group hidden after a drop.
            taskGroups() {
                return this.taskStatuses
                    .filter((s) => !this.taskStatusFilter || s.key === this.taskStatusFilter)
                    .map((s) => ({ ...s, tasks: this.tasks.filter((t) => t.status === s.key) }));
            },

            startTaskDrag(event, task) {
                event.dataTransfer.effectAllowed = 'move';
                // A private type, so dropping onto a text field doesn't paste anything.
                event.dataTransfer.setData('application/x-cove-task', String(task.id));
                // Changing the page during dragstart can cancel the drag, so the empty groups appear a tick later.
                setTimeout(() => { this.draggingTaskId = task.id; }, 0);
            },

            endTaskDrag() {
                this.draggingTaskId = null;
                this.dragOverKey = null;
            },

            dropTask(status) {
                const task = this.tasks.find((t) => t.id === this.draggingTaskId);
                this.endTaskDrag();

                if (task) {
                    this.updateTaskStatus(task, status);
                }
            },

            toggleShowCompleted() {
                this.showCompleted = !this.showCompleted;
                try { localStorage.setItem('showCompleted', this.showCompleted ? '1' : '0'); } catch (e) {}
            },

            taskCount(status) {
                return this.tasks.filter((t) => t.status === status).length;
            },

            taskStatusMeta(status) {
                return this.taskStatuses.find((s) => s.key === status) || this.taskStatuses[1];
            },

            taskAge(date) {
                if (!date) {
                    return '';
                }

                const minutes = (Date.now() - new Date(date).getTime()) / 60000;

                if (minutes < 60) return Math.max(1, Math.round(minutes)) + 'm';
                if (minutes < 1440) return Math.round(minutes / 60) + 'h';
                if (minutes < 43200) return Math.round(minutes / 1440) + 'd';

                return Math.round(minutes / 43200) + 'mo';
            },

            async addTask() {
                const title = this.taskForm.title.trim();
                if (!title) {
                    return;
                }

                const response = await fetch('/api/tasks', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ title, category: this.activeCategory }),
                });

                if (!response.ok) {
                    return;
                }

                const task = await response.json();
                this.taskForm.title = '';
                this.tasks.push(task);
            },

            // Moves the task straight away and puts it back if the server refuses.
            async updateTaskStatus(task, status) {
                if (task.status === status) {
                    return;
                }

                const previous = task.status;
                task.status = status;

                try {
                    const response = await fetch(`/api/tasks/${task.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ status }),
                    });

                    if (!response.ok) {
                        task.status = previous;
                        return;
                    }

                    const updated = await response.json();
                    task.status = updated.status;
                } catch (e) {
                    task.status = previous;
                }
            },

            toggleTaskCompleted(task) {
                this.updateTaskStatus(task, task.status === 'completed' ? 'active' : 'completed');
            },

            async deleteTask(task) {
                if (!(await this.askConfirm(`Delete "${task.title}"?`))) {
                    return;
                }

                await fetch(`/api/tasks/${task.id}`, { method: 'DELETE' });

                this.tasks = this.tasks.filter((t) => t.id !== task.id);
            },

            async clearTasks() {
                if (!(await this.askConfirm(`Delete all ${this.activeCategory === 'office' ? 'Work' : 'Personal'} tasks? This cannot be undone.`, 'Delete all'))) {
                    return;
                }

                const params = new URLSearchParams({ category: this.activeCategory });
                await fetch(`/api/tasks/clear?${params}`, { method: 'DELETE' });

                this.tasks = [];
            },

            makeNode(folder, parentId) {
                const node = {
                    id: folder.id,
                    name: folder.name,
                    parentId: parentId,
                    children: null,
                    expanded: false,
                };
                this.nodesById[folder.id] = node;
                return node;
            },

            buildBreadcrumbs(id) {
                const crumbs = [];
                let current = id ? this.nodesById[id] : null;

                while (current) {
                    crumbs.unshift({ id: current.id, name: current.name });
                    current = current.parentId ? this.nodesById[current.parentId] : null;
                }

                return crumbs;
            },

            visibleTree() {
                if (!this.search) {
                    return this.folderTree;
                }

                const term = this.search.toLowerCase();

                return this.folderTree.filter((node) => node.name.toLowerCase().includes(term));
            },

            async loadContent(folderId) {
                this.loading = true;
                this.selectedFolderId = folderId;
                this.breadcrumbs = this.buildBreadcrumbs(folderId);

                const params = new URLSearchParams({ category: this.activeCategory });
                if (folderId) {
                    params.set('folder_id', folderId);
                }

                const [foldersRes, itemsRes] = await Promise.all([
                    fetch(`${this.folderApiBase()}?${params}`),
                    fetch(`${this.contentApiBase()}?${params}`),
                ]);

                const foldersData = await foldersRes.json();
                const itemsData = await itemsRes.json();

                const childNodes = foldersData.map((f) => this.makeNode(f, folderId));

                if (folderId === null) {
                    this.folderTree = childNodes;
                } else {
                    const parentNode = this.nodesById[folderId];
                    if (parentNode) {
                        parentNode.children = childNodes;
                        parentNode.expanded = true;
                    }
                }

                this.subfolders = childNodes;

                if (this.activeApp === 'command') {
                    this.commands = itemsData;
                } else {
                    this.notes = itemsData;
                }

                this.loading = false;
            },

            selectFolder(id) {
                this.selectedNote = null;
                this.isEditingDetail = false;
                this.isNewNote = false;
                this.selectedCommand = null;
                this.isEditingCommandDetail = false;
                this.isNewCommand = false;
                this.loadContent(id);
            },

            async viewNote(note) {
                const res = await fetch(`/api/notes/${note.id}`);
                this.selectedNote = await res.json();
                this.isEditingDetail = false;
                this.isNewNote = false;
            },

            // Opens a note straight into edit mode from the list.
            async editNoteFromList(note) {
                await this.viewNote(note);
                this.startEditNote();
            },

            // "just now", "3 days ago", "2 months ago" for the note cards.
            noteAgo(date) {
                if (!date) {
                    return '';
                }

                const minutes = (Date.now() - new Date(date).getTime()) / 60000;
                const plural = (n, unit) => `${n} ${unit}${n === 1 ? '' : 's'} ago`;

                if (minutes < 1) return 'just now';
                if (minutes < 60) return plural(Math.round(minutes), 'minute');
                if (minutes < 1440) return plural(Math.round(minutes / 60), 'hour');
                if (minutes < 43200) return plural(Math.round(minutes / 1440), 'day');
                if (minutes < 525600) return plural(Math.round(minutes / 43200), 'month');

                return plural(Math.round(minutes / 525600), 'year');
            },

            // Name of the folder a note lives in, for the chip in the note header.
            noteFolderName(note) {
                const id = note.folder_id || null;

                return id && this.nodesById[id] ? this.nodesById[id].name : 'All Notes';
            },

            // True once a note has been changed after it was created (a minute of slack, since both stamps are set on creation).
            noteWasEdited(note) {
                if (!note.created_at || !note.updated_at) {
                    return false;
                }

                return new Date(note.updated_at).getTime() - new Date(note.created_at).getTime() > 60000;
            },

            async deleteNote(note) {
                if (!(await this.askConfirm(`Delete "${note.title || 'this note'}"? This cannot be undone.`))) {
                    return;
                }

                await fetch(`/api/notes/${note.id}`, { method: 'DELETE' });

                this.notes = this.notes.filter((n) => n.id !== note.id);

                if (this.selectedNote && this.selectedNote.id === note.id) {
                    this.selectedNote = null;
                    this.isEditingDetail = false;
                }
            },

            async toggleFolder(id) {
                const node = this.nodesById[id];
                if (!node) {
                    return;
                }

                if (node.children === null) {
                    const params = new URLSearchParams({ category: this.activeCategory, folder_id: id });
                    const res = await fetch(`${this.folderApiBase()}?${params}`);
                    const data = await res.json();
                    node.children = data.map((f) => this.makeNode(f, id));
                }

                node.expanded = !node.expanded;
            },

            renderTree(nodes, depth = 0) {
                return nodes.map((node) => {
                    const isSelected = this.selectedFolderId === node.id;
                    const rowClass = isSelected ? 'sidebar-item-active' : '';
                    const chevronClass = node.expanded ? 'rotate-90' : '';

                    let html = `
                        <div>
                            <div class="sidebar-item group cursor-pointer ${rowClass}"
                                 style="padding-left: ${12 + depth * 14}px; padding-top: 7px; padding-bottom: 7px; padding-right: 10px; margin-left: 12px; margin-right: 12px; width: calc(100% - 24px);"
                                 @click="selectFolder('${node.id}')">
                                <button type="button" @click.stop="toggleFolder('${node.id}')"
                                        class="w-3.5 h-3.5 flex items-center justify-center text-ink-500 hover:text-ink-100 shrink-0 transition-transform duration-150 ${chevronClass}">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 6l6 6-6 6"/></svg>
                                </button>
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" class="text-ink-500 shrink-0"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span class="truncate flex-1">${this.escapeHtml(node.name)}</span>
                                <div class="hidden group-hover:flex items-center gap-0.5 shrink-0">
                                    <button type="button" @click.stop="openRenameFolderModal(nodesById['${node.id}'])"
                                            title="Rename folder"
                                            class="w-5 h-5 rounded-md flex items-center justify-center text-ink-500 hover:text-amber-400 hover:bg-amber-500/10">
                                        <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    </button>
                                    <button type="button" @click.stop="deleteFolder(nodesById['${node.id}'])"
                                            title="Delete folder"
                                            class="w-5 h-5 rounded-md flex items-center justify-center text-ink-500 hover:text-red-400 hover:bg-red-500/10">
                                        <svg width="10" height="10" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/></svg>
                                    </button>
                                </div>
                            </div>
                            ${node.expanded && node.children ? `<div>${this.renderTree(node.children, depth + 1)}</div>` : ''}
                        </div>
                    `;

                    return html;
                }).join('');
            },

            // ---- Rich notes ----
            // Formatted notes are saved as HTML wrapped in <div class="nr"> (the server re-cleans it).
            // Older notes are plain text; they are shown escaped and converted when edited.

            noteIsRich(content) {
                return typeof content === 'string' && content.startsWith('<div class="nr">');
            },

            // Inner HTML for the editor and the read view.
            noteBodyHtml(content) {
                content = content ?? '';
                if (this.noteIsRich(content)) {
                    return content.slice('<div class="nr">'.length, -'</div>'.length);
                }
                return this.escapeHtml(content).replace(/\r?\n/g, '<br>');
            },

            // Plain-text version for the note cards.
            notePlain(content) {
                content = content ?? '';
                if (!this.noteIsRich(content)) return content;

                const doc = new DOMParser().parseFromString(content, 'text/html');
                doc.querySelectorAll('br').forEach((b) => b.replaceWith('\n'));
                doc.querySelectorAll('li').forEach((li) => li.prepend('• '));
                doc.querySelectorAll('div, p, li').forEach((el) => el.append('\n'));
                return doc.body.textContent.replace(/\n{2,}/g, '\n').trim();
            },

            // Read-view HTML: every <code> gets a Copy button on its right, inside one rectangle.
            // (Saved code has no attributes - the server strips them - so this match is exact.)
            noteViewHtml(content) {
                return this.noteBodyHtml(content).replace(
                    /<code>([\s\S]*?)<\/code>/g,
                    '<span class="code-block"><code>$1</code><button type="button" class="code-copy" title="Copy" aria-label="Copy">' + this.noteCopyIcon + '</button></span>'
                );
            },

            noteCopyIcon: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2.5"/><path d="M5 15V6a2 2 0 012-2h9"/></svg>',
            noteCheckIcon: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>',

            noteViewClick(e) {
                const button = e.target.closest ? e.target.closest('.code-copy') : null;
                if (!button) return;

                const code = button.parentElement.querySelector('code');
                if (!code) return;

                this.copyText(code.textContent).then((ok) => {
                    if (!ok) return;
                    button.classList.add('is-copied');
                    button.innerHTML = this.noteCheckIcon;
                    setTimeout(() => {
                        button.classList.remove('is-copied');
                        button.innerHTML = this.noteCopyIcon;
                    }, 1500);
                });
            },

            noteInitEditor(ed) {
                ed.innerHTML = this.noteBodyHtml(this.detailForm.content);
                this.noteTool.show = false;
                this.noteTool.linkMode = false;
                this.noteRange = null;
                this.noteMarkEmpty(ed);
                if (this.isNewNote) ed.focus();
            },

            noteMarkEmpty(ed) {
                const empty = ed.textContent.trim() === '' && !ed.querySelector('li');
                ed.classList.toggle('is-empty', empty);
                return empty;
            },

            // Copies the editor's HTML into the form (an empty editor saves as '' so "required" still applies).
            noteSync() {
                const ed = this.$refs.noteEditor;
                if (!ed) return;
                this.detailForm.content = this.noteMarkEmpty(ed)
                    ? ''
                    : '<div class="nr">' + ed.innerHTML.replace(/​/g, '') + '</div>';
            },

            noteClosest(node, selector) {
                const ed = this.$refs.noteEditor;
                const el = node && node.nodeType === 3 ? node.parentElement : node;
                const found = el && el.closest ? el.closest(selector) : null;
                return found && ed && ed.contains(found) ? found : null;
            },

            noteEditorInput(e) {
                const ed = this.$refs.noteEditor;
                if (e.inputType === 'insertText' && e.data === ' ') this.noteAutoList(ed);
                this.noteSync();
            },

            // ". " / "- " / "* " at the start of a line makes a bullet list, "1. " a numbered list.
            noteAutoList(ed) {
                const sel = window.getSelection();
                if (!sel.rangeCount || !sel.isCollapsed) return;

                const node = sel.anchorNode;
                if (!node || node.nodeType !== 3 || !ed.contains(node) || this.noteClosest(node, 'li')) return;

                const prev = node.previousSibling;
                if (prev && prev.nodeName !== 'BR') return;

                const before = node.textContent.slice(0, sel.anchorOffset);
                let command = null;
                let start = 1;

                if (/^[.\-*][  ]$/.test(before)) {
                    command = 'insertUnorderedList';
                } else if (/^\d{1,4}\.[  ]$/.test(before)) {
                    command = 'insertOrderedList';
                    start = parseInt(before, 10);
                }
                if (!command) return;

                const range = document.createRange();
                range.setStart(node, 0);
                range.setEnd(node, before.length);
                sel.removeAllRanges();
                sel.addRange(range);
                document.execCommand('delete');
                document.execCommand(command);

                if (command === 'insertOrderedList' && start !== 1) {
                    const list = this.noteClosest(window.getSelection().anchorNode, 'ol');
                    if (list) list.setAttribute('start', String(start));
                }
            },

            noteEditorKeydown(e) {
                // Tab nests a list item under the one above it; Shift+Tab moves it back out.
                if (e.key !== 'Tab' || e.ctrlKey || e.metaKey || e.altKey || e.isComposing) return;

                const sel = window.getSelection();
                if (!sel.rangeCount || !this.noteClosest(sel.anchorNode, 'li')) return;

                e.preventDefault();
                document.execCommand(e.shiftKey ? 'outdent' : 'indent');
                this.noteSync();
            },

            // Pasting always inserts plain text, so no foreign styles sneak in.
            noteEditorPaste(e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text/plain');
                if (text) document.execCommand('insertText', false, text);
            },

            // ---- Floating toolbar (code / link / red) ----

            noteSelectionOk() {
                const ed = this.$refs.noteEditor;
                const sel = window.getSelection();
                return !!ed && sel.rangeCount > 0 && !sel.isCollapsed && ed.contains(sel.anchorNode) && ed.contains(sel.focusNode);
            },

            noteIsRed() {
                try {
                    return /239,\s*68,\s*68/.test(document.queryCommandValue('foreColor'));
                } catch (err) {
                    return false;
                }
            },

            noteUpdateToolbar() {
                const t = this.noteTool;
                if (t.linkMode) return; // stay open while the link is typed

                if (!this.noteSelectionOk()) {
                    t.show = false;
                    return;
                }

                const sel = window.getSelection();
                const rect = sel.getRangeAt(0).getBoundingClientRect();
                if (!rect.width && !rect.height) {
                    t.show = false;
                    return;
                }

                t.code = !!this.noteClosest(sel.anchorNode, 'code') && !!this.noteClosest(sel.focusNode, 'code');
                t.link = !!this.noteClosest(sel.anchorNode, 'a');
                t.red = this.noteIsRed();

                const width = 300;
                t.x = Math.max(8, Math.min(rect.left + rect.width / 2 - 60, window.innerWidth - width - 8));
                t.y = rect.top - 46 < 8 ? rect.bottom + 8 : rect.top - 46;
                t.show = true;
            },

            noteToggleCode() {
                if (!this.noteSelectionOk()) return;
                const sel = window.getSelection();
                const existing = this.noteClosest(sel.anchorNode, 'code');

                if (existing) {
                    // Turn the whole code span back into plain text (done on the DOM so no
                    // leftover monospace styling is carried over by the browser).
                    const text = document.createTextNode(existing.textContent);
                    existing.replaceWith(text);
                    const range = document.createRange();
                    range.selectNodeContents(text);
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else {
                    document.execCommand('insertHTML', false, '<code>' + this.escapeHtml(sel.toString()) + '</code>');

                    // Put the caret after the new code span so typing carries on as normal text.
                    // The browser snaps a caret at the edge back into <code>, so park it behind an
                    // invisible spacer character (stripped again in noteSync).
                    const code = this.noteClosest(window.getSelection().anchorNode, 'code')
                        || this.noteClosest(window.getSelection().anchorNode?.previousSibling, 'code');
                    if (code) {
                        let next = code.nextSibling;
                        if (next && next.nodeType === 3) {
                            next.insertData(0, '​');
                        } else {
                            next = document.createTextNode('​');
                            code.after(next);
                        }
                        const after = document.createRange();
                        after.setStart(next, 1);
                        after.collapse(true);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(after);
                    }
                }

                this.noteSync();
                this.noteUpdateToolbar();
            },

            noteToggleRed() {
                if (!this.noteSelectionOk()) return;
                const ed = this.$refs.noteEditor;
                const wasRed = this.noteTool.red;

                document.execCommand('styleWithCSS', false, true);

                if (!wasRed) {
                    document.execCommand('foreColor', false, '#ef4444');
                } else {
                    // Clearing: colour the selection with a marker value, then unwrap those spans.
                    document.execCommand('foreColor', false, '#000001');

                    const sel = window.getSelection();
                    const saved = sel.rangeCount
                        ? { sc: sel.anchorNode, so: sel.anchorOffset, ec: sel.focusNode, eo: sel.focusOffset }
                        : null;

                    ed.querySelectorAll('span').forEach((span) => {
                        if (!/^rgb\(0,\s*0,\s*1\)$/.test(span.style.color)) return;
                        while (span.firstChild) span.parentNode.insertBefore(span.firstChild, span);
                        span.remove();
                    });

                    if (saved) {
                        try {
                            const range = document.createRange();
                            range.setStart(saved.sc, saved.so);
                            range.setEnd(saved.ec, saved.eo);
                            sel.removeAllRanges();
                            sel.addRange(range);
                        } catch (err) { /* selection collapsed; fine */ }
                    }
                }

                this.noteSync();
                this.noteUpdateToolbar();
            },

            noteOpenLink() {
                if (!this.noteSelectionOk()) return;
                const sel = window.getSelection();
                const link = this.noteClosest(sel.anchorNode, 'a');

                this.noteRange = sel.getRangeAt(0).cloneRange();
                this.noteTool.url = link ? link.getAttribute('href') : '';
                this.noteTool.linkMode = true;
                setTimeout(() => this.$refs.noteLinkInput && this.$refs.noteLinkInput.focus(), 0);
            },

            noteRestoreRange() {
                const ed = this.$refs.noteEditor;
                if (!ed || !this.noteRange) return false;
                ed.focus();
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(this.noteRange);
                return true;
            },

            noteCloseLink() {
                this.noteTool.linkMode = false;
                this.noteRestoreRange();
                this.noteUpdateToolbar();
            },

            noteApplyLink() {
                let url = (this.noteTool.url || '').trim();
                if (!url) {
                    this.noteCloseLink();
                    return;
                }
                if (!/^https?:\/\//i.test(url)) url = 'https://' + url.replace(/^\/+/, '');

                this.noteTool.linkMode = false;
                if (this.noteRestoreRange()) {
                    const existing = this.noteClosest(this.noteRange.commonAncestorContainer, 'a');
                    if (existing) existing.setAttribute('href', url);
                    else document.execCommand('createLink', false, url);
                }

                this.noteSync();
                this.noteUpdateToolbar();
            },

            noteRemoveLink() {
                this.noteTool.linkMode = false;
                if (this.noteRestoreRange()) document.execCommand('unlink');
                this.noteSync();
                this.noteUpdateToolbar();
            },

            startNewNote() {
                this.selectedNote = { id: null, title: '', content: '' };
                this.detailForm = { title: '', content: '' };
                this.errors = {};
                this.isNewNote = true;
                this.isEditingDetail = true;
            },

            startEditNote() {
                this.detailForm = { title: this.selectedNote.title, content: this.selectedNote.content };
                this.errors = {};
                this.isEditingDetail = true;
            },

            cancelEditDetail() {
                this.errors = {};

                if (this.isNewNote) {
                    this.selectedNote = null;
                    this.isNewNote = false;
                }

                this.isEditingDetail = false;
            },

            async saveDetail() {
                this.errors = {};

                const url = this.isNewNote ? '/api/notes' : `/api/notes/${this.selectedNote.id}`;
                const method = this.isNewNote ? 'POST' : 'PUT';
                const body = this.isNewNote
                    ? { ...this.detailForm, category: this.activeCategory, folder_id: this.selectedFolderId }
                    : { title: this.detailForm.title, content: this.detailForm.content };

                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                });

                if (response.status === 422) {
                    const errorBody = await response.json();
                    this.errors = errorBody.errors ?? {};
                    return;
                }

                const note = await response.json();

                if (this.isNewNote) {
                    this.notes.push(note);
                    this.isNewNote = false;
                } else {
                    const existing = this.notes.find((n) => n.id === note.id);
                    if (existing) {
                        existing.title = note.title;
                        existing.content = note.content;
                    }
                }

                this.selectedNote = note;
                this.isEditingDetail = false;
            },

            openFolderModal() {
                this.renamingFolderId = null;
                this.folderForm = { name: '' };
                this.errors = {};
                this.showFolderModal = true;
            },

            openRenameFolderModal(folder) {
                this.renamingFolderId = folder.id;
                this.folderForm = { name: folder.name };
                this.errors = {};
                this.showFolderModal = true;
            },

            async submitFolder() {
                this.errors = {};

                if (this.renamingFolderId) {
                    const response = await fetch(`${this.folderApiBase()}/${this.renamingFolderId}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ name: this.folderForm.name }),
                    });

                    if (response.status === 422) {
                        const errorBody = await response.json();
                        this.errors = errorBody.errors ?? {};
                        return;
                    }

                    const updated = await response.json();
                    const node = this.nodesById[updated.id];
                    if (node) {
                        node.name = updated.name;
                    }

                    this.showFolderModal = false;
                    return;
                }

                const response = await fetch(this.folderApiBase(), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        ...this.folderForm,
                        category: this.activeCategory,
                        folder_id: this.selectedFolderId,
                    }),
                });

                if (response.status === 422) {
                    const body = await response.json();
                    this.errors = body.errors ?? {};
                    return;
                }

                const folder = await response.json();
                const node = this.makeNode(folder, this.selectedFolderId);

                if (this.selectedFolderId === null) {
                    this.folderTree.push(node);
                } else {
                    const parent = this.nodesById[this.selectedFolderId];
                    if (parent) {
                        if (parent.children === null) {
                            parent.children = [];
                        }
                        parent.children.push(node);
                        parent.expanded = true;
                    }
                }

                this.subfolders.push(node);
                this.showFolderModal = false;
            },

            folderContainsCurrentView(folder) {
                if (this.selectedFolderId === folder.id) {
                    return true;
                }

                let current = this.selectedFolderId ? this.nodesById[this.selectedFolderId] : null;

                while (current) {
                    if (current.parentId === folder.id) {
                        return true;
                    }
                    current = current.parentId ? this.nodesById[current.parentId] : null;
                }

                return false;
            },

            async viewCommand(cmd) {
                const res = await fetch(`/api/commands/${cmd.id}`);
                this.selectedCommand = await res.json();
                this.isEditingCommandDetail = false;
                this.isNewCommand = false;
            },

            async deleteCommand(cmd) {
                if (!(await this.askConfirm(`Delete "${cmd.title || 'this command set'}"? This cannot be undone.`))) {
                    return;
                }

                await fetch(`/api/commands/${cmd.id}`, { method: 'DELETE' });

                this.commands = this.commands.filter((c) => c.id !== cmd.id);

                if (this.selectedCommand && this.selectedCommand.id === cmd.id) {
                    this.selectedCommand = null;
                    this.isEditingCommandDetail = false;
                }
            },

            startNewCommand() {
                this.selectedCommand = { id: null, title: '', rows: [] };
                this.commandDetailForm = { title: '', rows: [{ label: '', value: '' }] };
                this.errors = {};
                this.isNewCommand = true;
                this.isEditingCommandDetail = true;
            },

            startEditCommand() {
                this.commandDetailForm = {
                    title: this.selectedCommand.title,
                    rows: this.selectedCommand.rows.map((row) => ({ ...row })),
                };
                this.errors = {};
                this.isEditingCommandDetail = true;
            },

            cancelEditCommandDetail() {
                this.errors = {};

                if (this.isNewCommand) {
                    this.selectedCommand = null;
                    this.isNewCommand = false;
                }

                this.isEditingCommandDetail = false;
            },

            async saveCommandDetail() {
                this.errors = {};

                const rows = this.commandDetailForm.rows.filter((row) => row.label.trim() && row.value.trim());

                const url = this.isNewCommand ? '/api/commands' : `/api/commands/${this.selectedCommand.id}`;
                const method = this.isNewCommand ? 'POST' : 'PUT';
                const body = this.isNewCommand
                    ? { title: this.commandDetailForm.title, rows, category: this.activeCategory, folder_id: this.selectedFolderId }
                    : { title: this.commandDetailForm.title, rows };

                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                });

                if (response.status === 422) {
                    const errorBody = await response.json();
                    this.errors = errorBody.errors ?? {};
                    return;
                }

                const command = await response.json();

                if (this.isNewCommand) {
                    this.commands.push(command);
                    this.isNewCommand = false;
                } else {
                    const existing = this.commands.find((c) => c.id === command.id);
                    if (existing) {
                        existing.title = command.title;
                        existing.rows = command.rows;
                    }
                }

                this.selectedCommand = command;
                this.isEditingCommandDetail = false;
            },

            async deleteFolder(folder) {
                if (!(await this.askConfirm(`Delete "${folder.name}" and everything inside it? This cannot be undone.`))) {
                    return;
                }

                await fetch(`${this.folderApiBase()}/${folder.id}`, { method: 'DELETE' });

                const shouldNavigateAway = this.folderContainsCurrentView(folder);

                this.folderTree = this.folderTree.filter((n) => n.id !== folder.id);
                this.subfolders = this.subfolders.filter((n) => n.id !== folder.id);

                if (folder.parentId) {
                    const parent = this.nodesById[folder.parentId];
                    if (parent && parent.children) {
                        parent.children = parent.children.filter((n) => n.id !== folder.id);
                    }
                }

                delete this.nodesById[folder.id];

                if (shouldNavigateAway) {
                    this.selectFolder(folder.parentId ?? null);
                }
            },
        };
    }

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js');
        });
    }
</script>

</body>
</html>
