<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'

import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    canResetPassword: {
        type: Boolean,
        default: false,
    },
    status: {
        type: String,
        default: null,
    },
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head :title="`${appName} | Login`" />

    <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
        <header class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60">
            <div class="mx-auto max-w-7xl px-4">
                <AppHeader :can-login="canLogin" :can-register="canRegister" />
            </div>
        </header>

        <section class="relative">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mt-8 rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-sky-500 p-8 sm:p-12">
                    <div class="mx-auto max-w-md rounded-2xl bg-white/90 p-6 shadow-lg backdrop-blur-md dark:bg-zinc-900/85">
                        <div class="mb-6 text-center">
                            <h1 class="text-xl font-semibold sm:text-2xl">
                                Entrar no {{ appName }}
                            </h1>
                            <p class="mt-2 text-xs text-zinc-600 dark:text-zinc-300">
                                Acesse sua conta para gerenciar usuários, posts e tags.
                            </p>
                        </div>

                        <div
                            v-if="status"
                            class="mb-4 rounded-md border border-green-100 bg-green-50 px-4 py-3 text-xs font-medium text-green-700 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300"
                        >
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="voce@exemplo.com"
                                />

                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>

                            <div>
                                <div class="flex items-center justify-between">
                                    <InputLabel for="password" value="Password" />

                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >
                                        Esqueceu sua senha?
                                    </Link>
                                </div>

                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                />

                                <InputError class="mt-1" :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    <span class="text-xs text-gray-600 dark:text-gray-300">
                                        Lembrar semha
                                    </span>
                                </label>
                            </div>

                            <div class="pt-2">
                                <PrimaryButton
                                    class="flex w-full justify-center py-2.5 text-sm font-semibold"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Login
                                </PrimaryButton>
                            </div>

                            <p
                                v-if="canRegister"
                                class="pt-2 text-center text-xs text-zinc-600 dark:text-zinc-300"
                            >
                                Ainda não tem conta?
                                <Link
                                    :href="route('register')"
                                    class="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                                >
                                    Cadastre-se
                                </Link>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <AppFooter />
    </div>
</template>
