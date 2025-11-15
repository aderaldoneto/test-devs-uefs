<script setup>
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
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head :title="`${appName} | Register`" />

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
                                Criar conta no {{ appName }}
                            </h1>
                            <p class="mt-2 text-xs text-zinc-600 dark:text-zinc-300">
                                Registre-se para gerenciar usuários, posts e tags pela interface web.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Seu nome completo"
                                />

                                <InputError class="mt-1" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                    placeholder="voce@exemplo.com"
                                />

                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="password" value="Password" />

                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                />

                                <InputError class="mt-1" :message="form.errors.password" />
                            </div>

                            <div>
                                <InputLabel
                                    for="password_confirmation"
                                    value="Confirm Password"
                                />

                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Repita a senha"
                                />

                                <InputError
                                    class="mt-1"
                                    :message="form.errors.password_confirmation"
                                />
                            </div>

                            <label class="flex items-start gap-2 text-xs text-zinc-600 dark:text-zinc-300">
                                <input type="checkbox" class="mt-0.5" required>
                                    Concordo com os Termos de Uso e Política de Privacidade.
                            </label>

                            <div class="pt-2 space-y-3">
                                <PrimaryButton
                                    class="flex w-full justify-center py-2.5 text-sm font-semibold"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Register
                                </PrimaryButton>

                                <p class="text-center text-xs text-zinc-600 dark:text-zinc-300">
                                    Já possui uma conta?
                                    <Link
                                        :href="route('login')"
                                        class="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                                    >
                                        Faça login
                                    </Link>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <AppFooter />
    </div>
</template>
