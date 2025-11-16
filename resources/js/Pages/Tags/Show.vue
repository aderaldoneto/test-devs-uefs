<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'
import { formatLabel } from '@/Utils/PaginationButton'

const page = usePage()
const appName = page.props.app?.name ?? 'Blog API'

const props = defineProps({
  tag: {
    type: Object,
    required: true, // { id, name, slug }
  },
  posts: {
    type: Object,
    default: () => ({
      data: [],
      links: [],
    }),
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

const isAuthenticated = !!page.props.auth?.user
</script>

<template>
  <Head :title="`${appName} | Tag: ${tag.name}`" />

  <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
    <!-- Header -->
    <header
      class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md
             dark:border-zinc-800 dark:bg-zinc-950/60"
    >
      <AppHeader :can-login="canLogin" :can-register="canRegister" />
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10">
      <!-- Cabeçalho da Tag -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50">
            Publicações com a tag: <span class="text-indigo-600 dark:text-indigo-400">{{ tag.name }}</span>
          </h1>
          <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
            <strong>Slug:</strong> {{ tag.slug }}
          </p>
        </div>

        <Link
          href="/tags"
          class="text-xs font-semibold text-zinc-700 underline underline-offset-2 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
        >
          Voltar para Tags
        </Link>
      </div>

      <!-- Lista de posts -->
      <div
        v-if="posts.data.length"
        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
      >
        <section
          v-for="post in posts.data"
          :key="post.id"
          class="flex flex-col justify-between rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div>
            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
              {{ post.title }}
            </h2>

            <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
              Publicado em
              {{ new Date(post.created_at).toLocaleDateString('pt-BR') }}
              <span v-if="post.user">
                por <strong>{{ post.user.name }}</strong>
              </span>
            </p>

            <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300 line-clamp-3">
              {{ post.content }}
            </p>

            <!-- Outras tags do post (opcional) -->
            <div
              v-if="post.tags?.length"
              class="mt-3 flex flex-wrap gap-1"
            >
              <span
                v-for="t in post.tags"
                :key="t.id"
                class="rounded-full bg-zinc-100 px-2 py-0.5 text-[11px] text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
              >
                #{{ t.name }}
              </span>
            </div>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <Link
              :href="`/posts/${post.id}`"
              class="text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
            >
              Ver detalhes
            </Link>

            <div v-if="isAuthenticated" class="flex gap-3">
              <Link
                :href="`/posts/${post.id}/edit`"
                class="text-xs text-blue-600 hover:underline dark:text-blue-400"
              >
                Editar
              </Link>
            </div>
          </div>
        </section>
      </div>

      <!-- Nenhum post -->
      <div
        v-else
        class="rounded-2xl border border-dashed border-zinc-300 p-10 text-center dark:border-zinc-700"
      >
        <p class="text-sm text-zinc-600 dark:text-zinc-300">
          Nenhuma publicação encontrada para esta tag.
        </p>
      </div>

      <!-- Paginação -->
      <div v-if="posts.links?.length" class="mt-10 flex justify-center">
        <div class="flex gap-2">
          <button
            v-for="(link, i) in posts.links"
            :key="i"
            :disabled="!link.url"
            @click="
              router.get(link.url, {}, {
                preserveState: true,
                replace: true,
              })
            "
            class="rounded-lg px-3 py-1 text-sm"
            :class="[
              link.active
                ? 'bg-indigo-600 text-white'
                : 'bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800',
              !link.url && 'opacity-50 cursor-not-allowed',
            ]"
          >
            {{ formatLabel(link.label) }}
          </button>
        </div>
      </div>
    </main>

    <AppFooter />
  </div>
</template>
