<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({ login: '', password: '', password_confirmation: '' })
const submit = () => form.post('/register', { onFinish: () => form.reset('password', 'password_confirmation') })
</script>

<template>
  <Head title="Регистрация администратора" />
  <main class="grid min-h-screen place-items-center bg-[#0d0d13] px-5 py-10 text-white">
    <form class="w-full max-w-md rounded-xl border border-white/10 bg-white/[.035] p-8" @submit.prevent="submit">
      <a href="/" class="text-xs font-bold tracking-[.2em] text-violet-400">VOLGASHOT</a>
      <h1 class="mt-5 text-3xl font-semibold">Регистрация</h1>
      <p class="mt-2 text-sm text-zinc-500">Придумайте логин и пароль для управления сайтом.</p>
      <div class="mt-7 space-y-4">
        <label class="block text-sm text-zinc-300">Логин<input v-model="form.login" autocomplete="username" class="admin-input mt-2" required /></label>
        <label class="block text-sm text-zinc-300">Пароль<input v-model="form.password" type="password" autocomplete="new-password" class="admin-input mt-2" required /></label>
        <label class="block text-sm text-zinc-300">Повторите пароль<input v-model="form.password_confirmation" type="password" autocomplete="new-password" class="admin-input mt-2" required /></label>
      </div>
      <div v-if="Object.keys(form.errors).length" class="mt-4 rounded-md bg-red-500/10 p-3 text-sm text-red-300"><p v-for="error in form.errors" :key="error">{{ error }}</p></div>
      <button class="mt-6 w-full rounded-md bg-violet-600 px-4 py-3 font-semibold hover:bg-violet-500 disabled:opacity-50" :disabled="form.processing">Создать аккаунт</button>
      <p class="mt-5 text-center text-sm text-zinc-500">Уже есть аккаунт? <Link href="/login" class="text-violet-400">Войти</Link></p>
    </form>
  </main>
</template>
