<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'
import { ref } from 'vue'
import { formatLabel } from '@/Utils/PaginationButton'


const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
  posts: {
    type: Object,
    default: () => ({
      data: [],
      links: [],
    }),
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
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

const page = usePage()
const isAuthenticated = !!page.props.auth?.user

const search = ref(props.filters.search ?? '')

let t = null
function updateSearch() {
  clearTimeout(t)

  t = setTimeout(() => {
    router.get(
      '/posts',
      {
        search: search.value || undefined,
      },
      {
        preserveState: true,
        replace: true,
      },
    )
  }, 300)
}
</script>

<template>
  <Head :title="`${appName} | Posts`" />

  <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
    <header
      class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60"
    >
      <AppHeader :can-login="canLogin" :can-register="canRegister" />
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50">
            Posts
          </h1>
          <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
            Explore e gerencie os posts publicados pela API.
          </p>
        </div>

        <div v-if="isAuthenticated">
          <Link
            href="/posts/create"
            class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            Criar Post
          </Link>
        </div>
      </div>

      <div class="mb-6">
        <input
          v-model="search"
          @input="updateSearch"
          type="text"
          placeholder="Buscar por título ou conteúdo..."
          class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900"
        />
      </div>

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
            <h3 class="text-base font-semibold line-clamp-2">
              {{ post.title }}
            </h3>

            <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
              <span v-if="post.user && post.user.name">
                Por <strong>{{ post.user.name }}</strong>
              </span>

              <span v-if="post.created_at">
                <span v-if="post.user && post.user.name"> • </span>
                {{ new Date(post.created_at).toLocaleString('pt-BR') }}
              </span>
            </p>

            <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300 line-clamp-3">
              {{ post.content }}
            </p>

            <div
              v-if="post.tags && post.tags.length"
              class="mt-3 flex flex-wrap gap-2"
            >
              <span
                v-for="tag in post.tags"
                :key="tag.id ?? tag.slug ?? tag.name"
                class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
              >
                #{{ tag.name }}
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
                class="text-sm text-blue-600 hover:underline dark:text-blue-400"
              >
                Editar
              </Link>

              <button
                type="button"
                class="text-sm text-red-600 hover:underline dark:text-red-400"
                @click="
                  router.delete(`/posts/${post.id}`, {
                    preserveScroll: true,
                  })
                "
              >
                Excluir
              </button>
            </div>
          </div>
        </section>
      </div>

      <div
        v-else
        class="rounded-2xl border border-dashed border-zinc-300 p-10 text-center dark:border-zinc-700"
      >
        <p class="text-sm text-zinc-600 dark:text-zinc-300">
          Nenhum post encontrado.
        </p>
      </div>

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
