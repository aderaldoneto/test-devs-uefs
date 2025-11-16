<script setup>
import { Head, usePage, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'
import { formatLabel } from '@/Utils/PaginationButton'

const appName = usePage().props.app?.name ?? 'Blog API'

const props = defineProps({
  users: {
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

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

function goTo(link) {
  if (!link.url || link.active) return

  router.get(
    link.url,
    {},
    {
      preserveState: true,
      replace: true,
    },
  )
}

function deleteUser(user) {
  if (!isAuthenticated.value) return

  if (!confirm(`Tem certeza que deseja excluir o usuário "${user.name}"?`)) {
    return
  }

  router.delete(route('users.destroy', user.id), {
    preserveScroll: true,
  })
}

function editeUser(user) {
  if (!isAuthenticated.value) return

  router.get(route('users.edit', user.id), {
    preserveScroll: true,
  })
} 

</script>

<template>
  <Head :title="`${appName} | Usuários`" />

  <div class="min-h-screen bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-50">
    <header
      class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/70 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/60"
    >
      <div class="mx-auto max-w-7xl px-4">
        <AppHeader :can-login="canLogin" :can-register="canRegister" />
      </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold tracking-tight">Usuários</h1>
          <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
            Lista de usuários cadastrados. Visualização é pública; ações de edição ou exclusão exigem autenticação.
          </p>
        </div>

        <div v-if="isAuthenticated">
          <Link
            href="/users/create"
            class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
          >
            Adicionar
          </Link>
        </div>
      </div>

      <div
        class="overflow-hidden rounded-2xl border border-zinc-200/70 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
      >
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
          <thead class="bg-zinc-50/70 dark:bg-zinc-900/70">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400"
              >
                Nome
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400"
              >
                Email
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400"
              >
                Criado em
              </th>
              <th
                v-if="isAuthenticated"
                scope="col"
                class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400"
              >
                Ações
              </th>
            </tr>
          </thead>

          <tbody class="divide-y divide-zinc-100/80 dark:divide-zinc-800">
            <tr
              v-for="user in users.data"
              :key="user.id"
              class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/80"
            >
              <td class="px-6 py-3 text-sm font-medium">
                {{ user.name }}
              </td>
              <td class="px-6 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                {{ user.email }}
              </td>
              <td class="px-6 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                {{ new Date(user.created_at).toLocaleString('pt-BR') }}
              </td>
              <td v-if="isAuthenticated" class="px-6 py-3 text-right text-sm">
                <div class="flex items-center justify-end gap-3">
                  <button
                    type="button"
                    class="text-xs font-semibold text-blue-600 hover:underline dark:text-red-400"
                    @click="editeUser(user)"
                  >
                    Editar
                  </button>

                  <button
                    type="button"
                    class="text-xs font-semibold text-red-600 hover:underline dark:text-red-400"
                    @click="deleteUser(user)"
                  >
                    Excluir
                  </button>
                </div>
              </td>
            </tr>

            <tr v-if="users.data.length === 0">
              <td
                colspan="4"
                class="px-6 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400"
              >
                Nenhum usuário encontrado.
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginação -->
        <div
          v-if="users.links?.length"
          class="flex flex-wrap items-center justify-center gap-2 border-t border-zinc-200 px-6 py-4 text-sm dark:border-zinc-800"
        >
          <button
            v-for="(link, index) in users.links"
            :key="index"
            type="button"
            :disabled="!link.url"
            class="min-w-[2.25rem] rounded-md px-3 py-1"
            :class="[
              link.active
                ? 'bg-indigo-600 text-white'
                : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800',
              !link.url && 'opacity-50 cursor-not-allowed',
            ]"
            @click="goTo(link)"
          >
            {{ formatLabel(link.label) }}
          </button>
        </div>
      </div>
    </main>

    <AppFooter />
  </div>
</template>
