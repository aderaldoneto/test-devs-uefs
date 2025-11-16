<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'

const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <Head :title="`${appName} | Blog API`" />

    <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
        <header class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60">
            <AppHeader :can-login="canLogin" :can-register="canRegister" />
        </header>

        <section class="relative">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mt-8 rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-sky-500 p-8 sm:p-12">
                    <div class="max-w-xl rounded-2xl bg-white/90 p-6 backdrop-blur-md dark:bg-zinc-900/80">
                        <h1 class="text-2xl font-semibold sm:text-3xl">
                            Bem-vindo ao Blog da NETRA
                        </h1>

                        <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">
                            Esta é uma API RESTful construída em Laravel para gerenciar
                            <strong>Usuários</strong>, <strong>Posts</strong> e <strong>Tags</strong>. 
                            Esta interface em Vue/Inertia serve para explorar os dados expostos pela API.
                        </p>

                        <div class="mt-4 space-y-2 text-xs text-zinc-700 dark:text-zinc-300">
                            <p><strong>Acesso:</strong></p>
                            <ul class="list-disc space-y-1 pl-5">
                                <li>Qualquer visitante pode visualizar usuários, posts e tags.</li>
                                <li>Para <strong>criar, editar ou excluir</strong> qualquer recurso, é necessário estar autenticado.
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <Link
                                href="/users"
                                class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                Ver Usuários
                            </Link>
                            
                            <Link
                                href="/tags"
                                class="inline-flex items-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50 dark:hover:bg-zinc-800"
                            >
                                Ver Tags
                            </Link>

                            <Link
                                href="/posts"
                                class="inline-flex items-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50 dark:hover:bg-zinc-800"
                            >
                                Ver Posts
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <AppFooter />
    </div>
</template>
