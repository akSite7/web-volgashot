<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps<{ status?: string }>()

const form = useForm({ login: '', password: '', remember: false })
const submit = () => form.post('/login', { onFinish: () => form.reset('password') })
</script>

<template>
  <Head title="Вход в админку" />
  <main class="grid min-h-screen place-items-center bg-[#0d0d13] px-5 text-white">
    <form class="w-full max-w-md rounded-xl border border-white/10 bg-white/[.035] p-8" @submit.prevent="submit">
      <a href="/" class="text-xs font-bold tracking-[.2em] text-violet-400">VOLGASHOT</a>
      <h1 class="mt-5 text-3xl font-semibold">Вход в админку</h1>
      <p class="mt-2 text-sm text-zinc-500">Введите логин и пароль администратора.</p>
      <p v-if="status" class="mt-4 text-sm text-emerald-400">{{ status }}</p>
      <label class="mt-7 block text-sm text-zinc-300">Логин</label>
      <input v-model="form.login" autofocus autocomplete="username" class="admin-input mt-2" required />
      <p v-if="form.errors.login" class="mt-2 text-sm text-red-400">{{ form.errors.login }}</p>
      <label class="mt-5 block text-sm text-zinc-300">Пароль</label>
      <input v-model="form.password" type="password" autocomplete="current-password" class="admin-input mt-2" required />
      <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">{{ form.errors.password }}</p>
      <label class="mt-4 flex items-center gap-2 text-sm text-zinc-400"><input v-model="form.remember" type="checkbox" /> Запомнить меня</label>
      <button class="mt-6 w-full rounded-md bg-violet-600 px-4 py-3 font-semibold hover:bg-violet-500 disabled:opacity-50" :disabled="form.processing">Войти</button>
      <p class="mt-5 text-center text-sm text-zinc-500">Нет аккаунта? <Link href="/register" class="text-violet-400 hover:text-violet-300">Зарегистрироваться</Link></p>
      <p class="mt-4 text-center text-xs text-zinc-600">После 5 неудачных попыток вход блокируется на 1 час.</p>
    </form>
  </main>
</template>
