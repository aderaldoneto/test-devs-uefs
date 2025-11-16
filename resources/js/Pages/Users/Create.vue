<script setup>
import { Head, usePage, Link, router, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'

const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
  canLogin: {
    type: Boolean,
    default: true,
  },
  canRegister: {
    type: Boolean,
    default: true,
  },
})

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

// Inertia form
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// flash de sucesso vindo do backend (with('status', ...))
const successMessage = computed(() => page.props.flash?.status ?? '')

function submit() {
  if (!isAuthenticated.value) {
    alert('Você precisa estar autenticado para criar usuários.')
    return
  }

  form.post(route('users.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // se quiser limpar o formulário:
      form.reset('name', 'email', 'password', 'password_confirmation')
      // não precisa dar router.visit, o redirect do backend já levou pra users.index
    },
  })
}

function goBack() {
  router.visit(route('users.index'))
}
</script>

<template>
  <Head :title="`${appName} | Novo Usuário`" />

  <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
    <header
      class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60"
    >
      <div class="mx-auto max-w-7xl px-4">
        <AppHeader :can-login="canLogin" :can-register="canRegister" />
      </div>
    </header>

    <section class="relative">
      <div class="mx-auto max-w-7xl px-4">
        <div
          class="mt-8 rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-sky-500 p-8 sm:p-12"
        >
          <div
            class="mx-auto max-w-lg rounded-2xl bg-white/90 p-6 shadow-lg backdrop-blur-md dark:bg-zinc-900/85"
          >
            <div class="mb-6 flex items-center justify-between">
              <div>
                <h1 class="text-xl font-semibold sm:text-2xl">
                  Criar novo usuário
                </h1>
              </div>
              <Link
                :href="route('users.index')"
                class="text-xs font-semibold text-zinc-700 underline underline-offset-2 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
              >
                Voltar
              </Link>
            </div>

            <div
              v-if="!isAuthenticated"
              class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200"
            >
              Você precisa estar autenticado para criar usuários.
            </div>

            <div
              v-if="successMessage"
              class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200"
            >
              {{ successMessage }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
              <div>
                <label
                  for="name"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Nome
                </label>
                <input
                  id="name"
                  type="text"
                  v-model="form.name"
                  required
                  autocomplete="name"
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="Nome completo"
                />
                <p
                  v-if="form.errors.name"
                  class="mt-1 text-xs text-red-500"
                >
                  {{ form.errors.name }}
                </p>
              </div>

              <div>
                <label
                  for="email"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Email
                </label>
                <input
                  id="email"
                  type="email"
                  v-model="form.email"
                  required
                  autocomplete="username"
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="voce@exemplo.com"
                />
                <p
                  v-if="form.errors.email"
                  class="mt-1 text-xs text-red-500"
                >
                  {{ form.errors.email }}
                </p>
              </div>

              <div>
                <label
                  for="password"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Senha
                </label>
                <input
                  id="password"
                  type="password"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="Mínimo 8 caracteres"
                />
                <p
                  v-if="form.errors.password"
                  class="mt-1 text-xs text-red-500"
                >
                  {{ form.errors.password }}
                </p>
              </div>

              <div>
                <label
                  for="password_confirmation"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Confirmar senha
                </label>
                <input
                  id="password_confirmation"
                  type="password"
                  v-model="form.password_confirmation"
                  required
                  autocomplete="new-password"
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="Repita a senha"
                />
                <p
                  v-if="form.errors.password_confirmation"
                  class="mt-1 text-xs text-red-500"
                >
                  {{ form.errors.password_confirmation }}
                </p>
              </div>

              <div class="pt-2 flex items-center justify-end gap-3">
                <button
                  type="button"
                  class="text-xs font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
                  @click="goBack"
                >
                  Cancelar
                </button>

                <button
                  type="submit"
                  :disabled="form.processing || !isAuthenticated"
                  class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                >
                  {{ form.processing ? 'Salvando...' : 'Salvar usuário' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>
