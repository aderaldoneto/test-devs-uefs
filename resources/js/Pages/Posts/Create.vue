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
  tags: {
    type: Array,
    default: () => [],
  },
})

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

const form = useForm({
  title: '',
  content: '',
  tag_ids: [],
})

function submit() {
  if (!isAuthenticated.value) {
    alert('Você precisa estar autenticado para criar posts.')
    return
  }

  form.post('/posts', {
    onSuccess: () => {
      form.reset('title', 'content', 'tag_ids')
    },
  })
}

function goBack() {
  router.visit('/posts')
}
</script>

<template>
  <Head :title="`${appName} | Criar Post`" />

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
            class="mx-auto max-w-2xl rounded-2xl bg-white/90 p-6 shadow-lg backdrop-blur-md dark:bg-zinc-900/85"
          >
            <!-- Cabeçalho -->
            <div class="mb-6 flex items-center justify-between">
              <div>
                <h1 class="text-xl font-semibold sm:text-2xl">
                  Criar Post
                </h1>
                <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
                  Publique um novo conteúdo na API.
                </p>
              </div>
              <Link
                href="/posts"
                class="text-xs font-semibold text-zinc-700 underline underline-offset-2 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
              >
                Voltar
              </Link>
            </div>

            <!-- Aviso de autenticação -->
            <div
              v-if="!isAuthenticated"
              class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200"
            >
              Você precisa estar autenticado para criar posts.
            </div>

            <!-- Formulário -->
            <form @submit.prevent="submit" class="space-y-4">
              <!-- Título -->
              <div>
                <label
                  for="title"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Título
                </label>
                <input
                  id="title"
                  type="text"
                  v-model="form.title"
                  required
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="Título do post"
                />
                <p v-if="form.errors.title" class="mt-1 text-xs text-red-500">
                  {{ form.errors.title }}
                </p>
              </div>

              <!-- Conteúdo + Tags -->
              <div>
                <label
                  for="content"
                  class="block text-xs font-medium text-zinc-700 dark:text-zinc-200"
                >
                  Conteúdo
                </label>
                <textarea
                  id="content"
                  v-model="form.content"
                  required
                  rows="6"
                  class="mt-1 block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-50"
                  placeholder="Escreva aqui o conteúdo do post..."
                ></textarea>
                <p v-if="form.errors.content" class="mt-1 text-xs text-red-500">
                  {{ form.errors.content }}
                </p>

                <!-- Tags -->
                <div class="mt-4">
                  <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-200 mb-2">
                    Tags
                  </label>

                  <select
                    v-model="form.tag_ids"
                    multiple
                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900 h-40"
                  >
                    <option
                      v-for="tag in tags"
                      :key="tag.id"
                      :value="tag.id"
                    >
                      {{ tag.name }}
                    </option>
                  </select>

                  <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    Segure CTRL (Windows/Linux) ou CMD (Mac) para selecionar múltiplas tags.
                  </p>

                  <p v-if="form.errors.tag_ids" class="mt-1 text-xs text-red-500">
                    {{ form.errors.tag_ids }}
                  </p>
                </div>
              </div>

              <!-- Ações -->
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
                  {{ form.processing ? 'Publicando...' : 'Publicar post' }}
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
