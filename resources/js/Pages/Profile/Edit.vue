<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'

import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'

const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
})
</script>

<template>
    <Head :title="`${appName} | Perfil`" />

    <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
        <header class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60">
            <AppHeader :can-login="props.canLogin" :can-register="props.canRegister" />
        </header>

        <main class="mx-auto max-w-7xl px-4 py-10">
            <header class="mb-6">
                <nav class="text-xs text-zinc-500 dark:text-zinc-400">
                    <Link :href="route('welcome')" class="hover:underline">Início</Link>
                    <span class="mx-2">/</span>
                    <span>Perfil</span>
                </nav>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">Meu perfil</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                    Gerencie suas informações de conta, senha e preferências.
                </p>
            </header>

            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <section class="rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                            Informações do Perfil
                        </h2>
                        <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">
                            Atualize seu nome, email e outras informações básicas da sua conta.
                        </p>

                        <div class="mt-4">
                            <UpdateProfileInformationForm
                                :must-verify-email="props.mustVerifyEmail"
                                :status="props.status"
                                class="max-w-xl"
                            />
                        </div>
                    </section>

                    <section class="rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                            Alterar senha
                        </h2>
                        <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">
                            Escolha uma senha forte para manter sua conta protegida.
                        </p>

                        <div class="mt-4">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </section>
                </div>

                <section class="rounded-2xl border border-red-200/70 bg-red-50 p-5 shadow-sm dark:border-red-900/60 dark:bg-red-950/40">
                    <h2 class="text-sm font-semibold text-red-800 dark:text-red-200">
                        Excluir conta
                    </h2>
                    <p class="mt-1 text-xs text-red-700/90 dark:text-red-300/90">
                        Esta ação é permanente e removerá todos os dados associados à sua conta.
                        Proceda com cuidado.
                    </p>

                    <div class="mt-4">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </section>
            </div>
        </main>

        <AppFooter />
    </div>
</template>
