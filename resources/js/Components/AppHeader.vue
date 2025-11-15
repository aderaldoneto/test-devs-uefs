<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const appName = usePage().props.app.name
const menuOpen = ref(false)

const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
})


function closeOnClickOutside(event) {
    if (!event.target.closest('.user-menu')) {
        menuOpen.value = false
    }
}
if (typeof window !== 'undefined') {
    window.addEventListener('click', closeOnClickOutside)
}

</script>

<template> 
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:py-4">
    <!-- logo -->
    <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-zinc-800 text-zinc-100 dark:bg-zinc-200 dark:text-zinc-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 4h16v2H4zM4 7h10v2H4zM4 10h16v2H4zM4 13h10v2H4zM4 16h16v2H4z"/>
            </svg>
        </span>
        <span class="text-xl font-serif font-bold tracking-wide uppercase">{{ appName }}</span>
    </div>


    <!-- Auth links -->
    <nav class="flex items-center gap-2">

        <template v-if="$page.props.auth?.user">

            <Link :href="route('profile.edit')" class="text-sm underline underline-offset-4">{{ $page.props.auth.user.name }}</Link>

            <div class="relative user-menu">
                <button @click.stop="menuOpen = !menuOpen"
                        class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-700 dark:text-zinc-200" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 6h16a1 1 0 1 1 0 2H4a1 1 0 0 1 0-2Zm0 5h16a1 1 0 1 1 0 2H4a1 1 0 0 1 0-2Zm0 5h16a1 1 0 1 1 0 2H4a1 1 0 0 1 0-2Z"/>
                    </svg>
                </button>

                <div v-if="menuOpen"
                    class="absolute right-0 mt-2 w-44 rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                    <Link :href="route('profile.edit')"
                            class="block px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Perfil
                    </Link>

                    <div class="border-t border-zinc-200 dark:border-zinc-700 my-1"></div>
                    <Link :href="route('logout')" method="post" as="button"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                        Sair
                    </Link>
                </div>
            </div>

        </template>

        
        <Link v-if="!$page.props.auth?.user"
            :href="route('register')"
            class="rounded-full px-4 py-2 text-sm font-medium ring-1 ring-zinc-300 transition hover:bg-zinc-50 dark:ring-zinc-700 dark:hover:bg-zinc-900"
        >
            Criar conta
        </Link>
        <Link v-if="!$page.props.auth?.user"
            :href="route('login')"
            class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
        >
            Entrar
        </Link>
    </nav>
    </div>
</template>