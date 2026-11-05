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
    <meta name="theme-color" content="#f7fbfa">
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
                    class="w-[30px] h-[30px] shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-accent to-accent-deep text-white text-sm"
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
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-[0_1px_0_0_rgba(255,255,255,0.16)_inset,0_1px_3px_rgba(0,0,0,0.5)] transition-all duration-150 active:scale-[0.95]"
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

                    <div
                        x-show="!loading"
                        class="grid gap-4"
                        style="grid-template-columns: repeat(3, 1fr);"
                    >
                        <template x-for="folder in subfolders" :key="folder.id">
                            <div
                                @click="selectFolder(folder.id)"
                                class="tile-folder group"
                            >
                                <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-150">
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
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" class="text-amber-400/80"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <h3 class="font-semibold text-sm leading-snug text-fg tracking-tight pr-12" x-text="folder.name"></h3>
                            </div>
                        </template>

                        <template x-for="note in notes" :key="note.id">
                            <div
                                @click="viewNote(note)"
                                class="tile-note group"
                            >
                                <button
                                    @click.stop="deleteNote(note)"
                                    title="Delete note"
                                    class="absolute top-3 right-3 tile-action opacity-0 group-hover:opacity-100 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150"
                                >
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                    </svg>
                                </button>
                                <h3 class="font-semibold text-sm leading-snug text-fg tracking-tight pr-6" x-text="note.title"></h3>
                                <p class="text-sm text-ink-400 line-clamp-4 whitespace-pre-line leading-relaxed" x-text="note.content"></p>
                            </div>
                        </template>
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
                <div class="max-w-2xl">
                    <button @click="selectedNote = null; isEditingDetail = false; isNewNote = false;" class="flex items-center gap-1.5 text-sm text-ink-500 hover:text-fg transition-colors mb-6">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                        Back
                    </button>

                    <!-- View mode -->
                    <template x-if="!isEditingDetail">
                        <div>
                            <h1 class="text-2xl font-bold text-fg tracking-tight mb-5" x-text="selectedNote.title"></h1>
                            <p class="text-sm text-ink-300 whitespace-pre-line leading-relaxed"><template x-for="(seg, i) in noteSegments(selectedNote.content)" :key="i"><span x-data="{ copied: false }"><template x-if="seg.type === 'text'"><span x-text="seg.value"></span></template><template x-if="seg.type === 'code'"><button type="button" title="Click to copy" @click="copyText(seg.value).then((ok) => { if (ok) { copied = true; setTimeout(() => copied = false, 1500); } })" class="inline px-1.5 py-0.5 rounded-md border font-mono text-[0.85em] cursor-pointer transition-colors" :class="copied ? 'border-emerald-400/50 text-emerald-400 bg-emerald-400/10' : 'border-fg/[0.16] bg-fg/[0.06] text-fg hover:border-accent hover:text-accent'" x-text="copied ? 'Copied' : seg.value"></button></template><template x-if="seg.type === 'link'"><a :href="seg.href" target="_blank" rel="noopener noreferrer" class="text-accent underline underline-offset-2 hover:opacity-80" x-text="seg.value"></a></template></span></template></p>

                            <div class="mt-10 flex items-center gap-2">
                                <button @click="startEditNote()" class="btn-secondary">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    Edit
                                </button>
                                <button @click="deleteNote(selectedNote)" class="btn-ghost text-red-400 hover:bg-red-500/10 hover:text-red-300">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Edit / create mode -->
                    <template x-if="isEditingDetail">
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
                                <textarea
                                    x-model="detailForm.content"
                                    @input="noteAutoFormat($event)"
                                    @keydown.enter="noteListEnter($event)"
                                    @keydown.backspace="noteListBackspace($event)"
                                    placeholder="Write something…"
                                    class="w-full min-h-[50vh] bg-transparent text-sm text-ink-200 placeholder-ink-600 outline-none leading-relaxed resize-none [field-sizing:content]"
                                ></textarea>
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
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-[0_1px_0_0_rgba(255,255,255,0.16)_inset,0_1px_3px_rgba(0,0,0,0.5)] transition-all duration-150 active:scale-[0.95]"
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

                <form @submit.prevent="addTask()" class="mt-4 flex items-center gap-3.5 px-1 py-3.5 border-b border-fg/[0.08]">
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

                <div x-show="!taskLoading && visibleTasks().length > 0">
                    <template x-for="task in visibleTasks()" :key="task.id">
                        <div class="group flex items-center gap-3.5 px-1 py-3.5 border-b border-fg/[0.08] text-[15px]">
                            <button
                                type="button"
                                @click="toggleTaskCompleted(task)"
                                :title="task.status === 'completed' ? 'Mark not done' : 'Mark done'"
                                class="w-5 h-5 shrink-0 rounded-full border-[1.75px] flex items-center justify-center transition-colors text-ink-950"
                                :class="task.status === 'completed' ? 'bg-emerald-400 border-emerald-400' : 'border-ink-500 hover:border-accent'"
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
        return {
            showSplash: true,
            activeApp: 'task',
            activeCategory: localStorage.getItem('activeCategory') || 'office',
            tasks: [],
            taskStatusFilter: null,
            taskForm: { title: '' },
            taskLoading: false,
            taskStatuses: [
                { key: 'critical', label: 'Critical', color: 'rgb(var(--st-critical))', border: 'rgb(var(--st-critical) / 0.35)' },
                { key: 'active', label: 'Active', color: 'rgb(var(--st-active))', border: 'rgb(var(--st-active) / 0.35)' },
                { key: 'upcoming', label: 'Upcoming', color: 'rgb(var(--st-upcoming))', border: 'rgb(var(--st-upcoming) / 0.35)' },
                { key: 'completed', label: 'Completed', color: 'rgb(var(--st-completed))', border: 'rgb(var(--st-completed) / 0.35)' },
            ],
            folderTree: [],
            nodesById: {},
            selectedFolderId: null,
            breadcrumbs: [],
            subfolders: [],
            notes: [],
            selectedNote: null,
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

            async updateTaskStatus(task, status) {
                const response = await fetch(`/api/tasks/${task.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ status }),
                });

                if (!response.ok) {
                    return;
                }

                const updated = await response.json();
                task.status = updated.status;
            },

            toggleTaskCompleted(task) {
                this.updateTaskStatus(task, task.status === 'completed' ? 'active' : 'completed');
            },

            async deleteTask(task) {
                if (!confirm(`Delete "${task.title}"?`)) {
                    return;
                }

                await fetch(`/api/tasks/${task.id}`, { method: 'DELETE' });

                this.tasks = this.tasks.filter((t) => t.id !== task.id);
            },

            async clearTasks() {
                if (!confirm(`Delete all ${this.activeCategory} tasks? This cannot be undone.`)) {
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

            async deleteNote(note) {
                if (!confirm(`Delete "${note.title || 'this note'}"? This cannot be undone.`)) {
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

            // Splits note text into plain / code / link pieces for the view mode:
            // `like this` is copyable code, <https://example.com> is a clickable link.
            // Only http(s) links (or bare domains, which get https://) count; anything
            // else inside <> stays as typed.
            noteSegments(content) {
                const text = String(content ?? '');
                const segments = [];
                const pattern = /`([^`\n]+)`|<([^<>\s]+)>/g;
                let last = 0;
                let m;

                const pushText = (value) => {
                    if (!value) return;
                    const prev = segments[segments.length - 1];
                    if (prev && prev.type === 'text') prev.value += value;
                    else segments.push({ type: 'text', value });
                };

                while ((m = pattern.exec(text)) !== null) {
                    pushText(text.slice(last, m.index));
                    last = m.index + m[0].length;

                    if (m[1] !== undefined) {
                        segments.push({ type: 'code', value: m[1] });
                        continue;
                    }

                    let href = null;
                    if (/^https?:\/\/[^\s/]+/i.test(m[2])) href = m[2];
                    else if (/^(www\.)?[a-z0-9-]+(\.[a-z0-9-]+)+(:\d+)?([/?#]\S*)?$/i.test(m[2])) href = 'https://' + m[2];

                    if (href) segments.push({ type: 'link', value: m[2], href });
                    else pushText(m[0]);
                }

                pushText(text.slice(last));
                return segments;
            },

            // ---- List support for the note editor (content stays plain text) ----
            // Typing ". " (or "- " / "* ") at the start of a line makes a bullet,
            // "1 " makes a numbered item. Enter continues a list, Enter on an empty
            // item or Backspace right after a marker ends it.

            // Replace [start, end) with text, keeping the browser's undo stack where possible.
            noteReplace(el, start, end, text) {
                el.focus();
                el.setSelectionRange(start, end);
                if (!document.execCommand('insertText', false, text)) {
                    el.setRangeText(text, start, end, 'end');
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },

            noteAutoFormat(e) {
                if (e.inputType !== 'insertText' || e.data !== ' ') return;

                const el = e.target;
                const caret = el.selectionStart;
                const lineStart = el.value.lastIndexOf('\n', caret - 1) + 1;
                const typed = el.value.slice(lineStart, caret);

                let match = typed.match(/^([ \t]*)[.\-*] $/);
                if (match) {
                    this.noteReplace(el, lineStart, caret, match[1] + '• ');
                    return;
                }

                match = typed.match(/^([ \t]*)(\d+) $/);
                if (match) {
                    this.noteReplace(el, lineStart, caret, match[1] + match[2] + '. ');
                }
            },

            noteListEnter(e) {
                if (e.isComposing || e.shiftKey || e.ctrlKey || e.metaKey || e.altKey) return;

                const el = e.target;
                if (el.selectionStart !== el.selectionEnd) return;

                const value = el.value;
                const caret = el.selectionStart;
                const lineStart = value.lastIndexOf('\n', caret - 1) + 1;
                const match = value.slice(lineStart, caret).match(/^([ \t]*)(•|(\d+)\.) (.*)$/);
                if (!match) return;

                e.preventDefault();

                const [, indent, marker, num, text] = match;
                const lineEndIdx = value.indexOf('\n', caret);
                const lineEnd = lineEndIdx === -1 ? value.length : lineEndIdx;

                // Empty item (nothing typed after the marker, nothing after the caret): end the list.
                if (text === '' && lineEnd === caret) {
                    this.noteReplace(el, lineStart, caret, '');
                    return;
                }

                const rest = value.slice(caret, lineEnd);

                if (num === undefined) {
                    this.noteReplace(el, caret, lineEnd, '\n' + indent + '• ' + rest);
                    el.setSelectionRange(caret + 1 + indent.length + 2, caret + 1 + indent.length + 2);
                    return;
                }

                // Numbered: insert the next number, then renumber the items that follow it.
                let next = parseInt(num, 10) + 1;
                let out = '\n' + indent + next + '. ' + rest;
                let end = lineEnd;
                const numbered = new RegExp('^' + indent + '(\\d+)\\. ');

                while (end < value.length) {
                    const nextStart = end + 1;
                    const nextEndIdx = value.indexOf('\n', nextStart);
                    const nextEnd = nextEndIdx === -1 ? value.length : nextEndIdx;
                    const line = value.slice(nextStart, nextEnd);
                    if (!numbered.test(line)) break;

                    next += 1;
                    out += '\n' + line.replace(numbered, indent + next + '. ');
                    end = nextEnd;
                }

                const caretAfter = caret + 1 + indent.length + String(parseInt(num, 10) + 1).length + 2;
                this.noteReplace(el, caret, end, out);
                el.setSelectionRange(caretAfter, caretAfter);
            },

            noteListBackspace(e) {
                if (e.isComposing) return;

                const el = e.target;
                if (el.selectionStart !== el.selectionEnd) return;

                const caret = el.selectionStart;
                const lineStart = el.value.lastIndexOf('\n', caret - 1) + 1;

                if (/^[ \t]*(•|\d+\.) $/.test(el.value.slice(lineStart, caret))) {
                    e.preventDefault();
                    this.noteReplace(el, lineStart, caret, '');
                }
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
                if (!confirm(`Delete "${cmd.title || 'this command set'}"? This cannot be undone.`)) {
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
                if (!confirm(`Delete "${folder.name}" and everything inside it? This cannot be undone.`)) {
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
