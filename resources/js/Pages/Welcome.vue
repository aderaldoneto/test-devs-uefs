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
        <!-- Header -->
        <header class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60">
            <AppHeader :can-login="canLogin" :can-register="canRegister" />
        </header>

        <!-- Hero / Banner -->
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
                                :href="route().has('users.index') ? route('users.index') : '#'"
                                class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                Ver Usuários
                            </Link>

                            <Link
                                :href="route().has('posts.index') ? route('posts.index') : '#'"
                                class="inline-flex items-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50 dark:hover:bg-zinc-800"
                            >
                                Ver Posts
                            </Link>

                            <Link
                                :href="route().has('tags.index') ? route('tags.index') : '#'"
                                class="inline-flex items-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50 dark:hover:bg-zinc-800"
                            >
                                Ver Tags
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-10">
            <h2 class="mb-4 text-lg font-semibold">Recursos</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <section class="flex flex-col justify-between rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div>
                        <h3 class="text-base font-semibold">Usuários</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                            CRUD completo de usuários, incluindo validação de dados, paginação e pesquisa por nome/email.
                        </p>
                        <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">
                            <strong>Observação:</strong> criação, edição e exclusão exigem autenticação; listagem e visualização são públicas.
                        </p>
                    </div>
                    <div class="mt-4">
                        <Link
                            :href="route().has('users.index') ? route('users.index') : '#'"
                            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                        >
                            Ir para Usuários
                        </Link>
                    </div>
                </section>

                <section class="flex flex-col justify-between rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div>
                        <h3 class="text-base font-semibold">Posts</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                            Posts associados a usuários, com conteúdo, data de publicação e relação Many-to-Many com tags.
                        </p>
                        <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">
                            <strong>Observação:</strong> qualquer visitante pode ver posts; criar, atualizar ou remover posts exige login.
                        </p>
                    </div>
                    <div class="mt-4">
                        <Link
                            :href="route().has('posts.index') ? route('posts.index') : '#'"
                            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                        >
                            Ir para Posts
                        </Link>
                    </div>
                </section>

                <section class="flex flex-col justify-between rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div>
                        <h3 class="text-base font-semibold">Tags</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                            Tags reutilizáveis que podem ser vinculadas a vários posts, com validação de unicidade para nome e slug.
                        </p>
                        <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">
                            <strong>Observação:</strong> visualização é pública; operações de escrita são protegidas.
                        </p>
                    </div>
                    <div class="mt-4">
                        <Link
                            :href="route().has('tags.index') ? route('tags.index') : '#'"
                            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                        >
                            Ir para Tags
                        </Link>
                    </div>
                </section>
            </div>
        </main>

        <AppFooter />
    </div>
</template>
