<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'

const page = usePage()
const appName = page.props.app?.name ?? 'Blog API'
const isAuthenticated = !!page.props.auth?.user

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
})

function goBack() {
  if (window.history.length > 1 && document.referrer) {
    window.history.back()
    return
  }

  router.visit(route('posts.index'))
}
</script>

<template>
  <Head :title="`${appName} | ${props.post.title}`" />

  <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">

    <header
      class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70
             backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60"
    >
      <div class="mx-auto max-w-7xl px-4">
        <AppHeader :can-login="true" :can-register="true" />
      </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-10">

      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">
          {{ props.post.title }}
        </h1>

        <button
          type="button"
          @click="goBack"
          class="rounded-xl bg-zinc-200 px-4 py-2 text-sm font-semibold
                text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-800 dark:text-zinc-100
                dark:hover:bg-zinc-700"
        >
          Voltar
        </button>
      </div>

      <div
        class="rounded-2xl border border-zinc-200/70 bg-white p-6 shadow-sm
               dark:border-zinc-800 dark:bg-zinc-900"
      >
        <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-300">
          <p>
            <strong class="text-zinc-800 dark:text-zinc-100">Autor:</strong>
            {{ props.post.user?.name }}
          </p>

          <p>
            <strong class="text-zinc-800 dark:text-zinc-100">Criado em:</strong>
            {{ new Date(props.post.created_at).toLocaleString('pt-BR') }}
          </p>
        </div>

        <div class="prose prose-zinc dark:prose-invert max-w-none mb-6">
          <p>{{ props.post.content }}</p>
        </div>

        <div v-if="props.post.tags?.length" class="mb-6">
          <h3 class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
            Tags
          </h3>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="tag in props.post.tags"
              :key="tag.id"
              class="rounded-full bg-indigo-600/10 px-3 py-1 text-xs font-semibold
                     text-indigo-600 dark:bg-indigo-400/10 dark:text-indigo-300"
            >
              {{ tag.name }}
            </span>
          </div>
        </div>

        <div v-if="isAuthenticated" class="mt-6 flex gap-3">
          <Link
            :href="route('posts.edit', props.post.id)"
            class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2
                   text-sm font-semibold text-white hover:bg-blue-500"
          >
            Editar
          </Link>

          <button
            type="button"
            class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2
                   text-sm font-semibold text-white hover:bg-red-500"
            @click="router.delete(route('posts.destroy', props.post.id))"
          >
            Excluir
          </button>
        </div>
      </div>
    </main>

    <AppFooter />
  </div>
</template>
