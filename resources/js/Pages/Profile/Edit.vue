<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import { Head, usePage } from '@inertiajs/vue3'

const appName = usePage().props.app?.name ?? 'Blog API'

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})
</script>

<template>
    <Head :title="`${appName} | Profile`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                        Perfil
                    </h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                        Gerencie suas informações de conta, senha e preferências.
                    </p>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <section class="rounded-2xl border border-zinc-200/70 bg-white/90 p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        Informações do Perfil
                    </h2>
                    <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">
                        Atualize seu nome, email e outras informações básicas da sua conta.
                    </p>

                    <div class="mt-4">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                </section>

                <section class="rounded-2xl border border-zinc-200/70 bg-white/90 p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
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

                <section class="rounded-2xl border border-red-200/70 bg-red-50/80 p-6 shadow-sm dark:border-red-900/60 dark:bg-red-950/40">
                    <h2 class="text-sm font-semibold text-red-800 dark:text-red-200">
                        Excluir conta
                    </h2>
                    <p class="mt-1 text-xs text-red-700/90 dark:text-red-300/90">
                        Esta ação é permanente e removerá todos os dados associados à sua conta. Proceda com cuidado.
                    </p>

                    <div class="mt-4">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
