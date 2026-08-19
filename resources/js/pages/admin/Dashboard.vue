<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

type Product = { id: number; name: string; type: 'shot' | 'buckshot'; description: string | null; price_per_kg: string; photo_url: string | null }
type Lead = { id: number; name: string; phone: string | null; email: string | null; comment: string | null; status: string; created_at: string }
type AdminUser = { id: number; login: string; approved_at: string | null; created_at: string }

defineProps<{ products: Product[]; leads: Lead[]; users: AdminUser[] }>()
const tab = ref<'products' | 'leads' | 'users'>('products')
const editing = ref<Product | null>(null)
const showProductForm = ref(false)
const form = useForm<{ name: string; type: 'shot' | 'buckshot'; description: string; price_per_kg: string; photo: File | null }>({ name: '', type: 'shot', description: '', price_per_kg: '', photo: null })
const editingUser = ref<AdminUser | null>(null)
const userForm = useForm({ login: '', password: '' })
const formatPrice = (price: string) => Math.round(Number(price)).toLocaleString('ru-RU')

function edit(product: Product) {
  editing.value = product
  showProductForm.value = true
  form.name = product.name
  form.type = product.type
  form.description = product.description ?? ''
  form.price_per_kg = String(Math.round(Number(product.price_per_kg)))
  form.photo = null
}
function reset() { editing.value = null; showProductForm.value = false; form.reset(); form.clearErrors() }
function createProduct() { reset(); showProductForm.value = true }
function save() {
  const options = { forceFormData: true, onSuccess: reset }
  if (editing.value) form.post(`/admin/products/${editing.value.id}`, options)
  else form.post('/admin/products', options)
}
function removeProduct(id: number) { if (confirm('Удалить товар?')) router.delete(`/admin/products/${id}`) }
function removeLead(id: number) { if (confirm('Удалить заявку?')) router.delete(`/admin/leads/${id}`) }
function setStatus(id: number, status: string) { router.patch(`/admin/leads/${id}`, { status }, { preserveScroll: true }) }
function approveUser(id: number) { router.patch(`/admin/users/${id}/approve`, {}, { preserveScroll: true }) }
function editUser(user: AdminUser) { editingUser.value = user; userForm.login = user.login; userForm.password = ''; userForm.clearErrors() }
function closeUserForm() { editingUser.value = null; userForm.reset(); userForm.clearErrors() }
function saveUser() {
  if (!editingUser.value) return
  userForm.patch(`/admin/users/${editingUser.value.id}`, { preserveScroll: true, onSuccess: closeUserForm })
}
function removeUser(id: number) { if (confirm('Удалить пользователя?')) router.delete(`/admin/users/${id}`, { preserveScroll: true }) }
</script>

<template>
  <Head title="Админка" />
  <div class="min-h-screen bg-[#0d0d13] text-zinc-100">
    <header class="border-b border-[#20202a]"><div class="mx-auto flex h-[72px] max-w-[1272px] items-center justify-between px-5 xl:px-0"><div><span class="text-[13px] font-semibold text-slate-500">VOLGASHOT</span></div><div class="flex gap-5 text-[13px]"><a href="/" class="text-zinc-400 transition-colors hover:text-white">На сайт</a><button class="cursor-pointer text-zinc-500 transition-colors hover:text-red-400" @click="router.post('/logout')">Выйти</button></div></div></header>
    <main class="mx-auto grid max-w-[1272px] gap-8 px-5 py-10 md:grid-cols-[180px_minmax(0,1fr)] xl:px-0">
      <aside class="h-fit overflow-hidden rounded-[2px] border border-white/5 bg-white/[.025] md:mt-[68px]">
        <button class="block w-full cursor-pointer px-4 py-3 text-left text-[13px] transition-colors" :class="tab === 'products' ? 'bg-[#5548e8] text-white' : 'text-zinc-500 hover:bg-white/[.035] hover:text-zinc-300'" @click="tab = 'products'">Товары</button>
        <button class="block w-full cursor-pointer border-t border-white/5 px-4 py-3 text-left text-[13px] transition-colors" :class="tab === 'leads' ? 'bg-[#5548e8] text-white' : 'text-zinc-500 hover:bg-white/[.035] hover:text-zinc-300'" @click="tab = 'leads'">Заявки</button>
        <button class="block w-full cursor-pointer border-t border-white/5 px-4 py-3 text-left text-[13px] transition-colors" :class="tab === 'users' ? 'bg-[#5548e8] text-white' : 'text-zinc-500 hover:bg-white/[.035] hover:text-zinc-300'" @click="tab = 'users'">Пользователи</button>
      </aside>

      <div class="min-w-0">

      <section v-if="tab === 'products'">
        <div class="mb-5 flex items-center justify-between">
          <h1 class="text-[32px] font-semibold tracking-[-0.03em] text-zinc-100">Товары</h1>
          <button v-if="!showProductForm" class="cursor-pointer rounded-[2px] bg-[#5548e8] px-4 py-[10px] text-[13px] font-semibold transition-colors hover:bg-[#5d51e9]" @click="createProduct">Добавить товар</button>
        </div>

        <form v-if="showProductForm" class="admin-card mb-6" @submit.prevent="save">
          <div class="flex items-center justify-between"><h2 class="text-xl font-semibold">{{ editing ? 'Редактировать товар' : 'Добавить товар' }}</h2><button type="button" class="text-sm text-zinc-400 hover:text-white" @click="reset">Закрыть</button></div>
          <div class="mt-5 grid gap-4 md:grid-cols-2">
            <label class="block text-sm">Название<input v-model="form.name" class="admin-input mt-2" required /></label>
            <label class="block text-sm">Тип товара<select v-model="form.type" class="admin-input mt-2" required><option value="shot">Дробь</option><option value="buckshot">Картечь</option></select></label>
            <label class="block text-sm md:col-span-2">Описание<textarea v-model="form.description" class="admin-input mt-2 min-h-24 resize-y" /></label>
            <label class="block text-sm">Цена за 1 кг, ₽<input v-model="form.price_per_kg" type="number" min="0" step="1" class="admin-input mt-2" required /></label>
            <label class="block text-sm">Фото (до 5 МБ)<input type="file" accept="image/*" class="mt-3 block w-full text-sm text-zinc-400" @change="form.photo = ($event.target as HTMLInputElement).files?.[0] ?? null" /></label>
          </div>
          <div v-if="Object.keys(form.errors).length" class="mt-4 text-sm text-red-400"><p v-for="error in form.errors" :key="error">{{ error }}</p></div>
          <button class="mt-5 cursor-pointer rounded-[2px] bg-[#5548e8] px-4 py-[10px] text-[13px] font-semibold transition-colors hover:bg-[#5d51e9] disabled:opacity-50" :disabled="form.processing">{{ editing ? 'Сохранить' : 'Добавить' }}</button>
        </form>

        <div v-else class="overflow-x-auto rounded-[2px] border border-white/5">
          <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="bg-white/[.04] text-zinc-500"><tr><th class="p-4">Фото</th><th class="p-4">Название</th><th class="p-4">Тип</th><th class="p-4">Описание</th><th class="p-4">Цена</th><th class="p-4 text-right">Действия</th></tr></thead>
            <tbody>
              <tr v-for="product in products" :key="product.id" class="border-t border-white/5">
                <td class="p-4"><div class="h-14 w-14 overflow-hidden rounded-[2px] bg-white/5"><img v-if="product.photo_url" :src="product.photo_url" :alt="product.name" class="h-full w-full object-cover" /><div v-else class="grid h-full place-items-center text-[10px] text-zinc-600">Нет фото</div></div></td>
                <td class="p-4 font-medium">{{ product.name }}</td>
                <td class="p-4 text-zinc-400">{{ product.type === 'shot' ? 'Дробь' : 'Картечь' }}</td>
                <td class="max-w-sm p-4 text-zinc-500"><p class="line-clamp-2">{{ product.description || '—' }}</p></td>
                <td class="whitespace-nowrap p-4 font-medium text-[#756bea]">{{ formatPrice(product.price_per_kg) }} ₽ / кг</td>
                <td class="p-4"><div class="flex justify-end gap-2"><button class="cursor-pointer rounded-[2px] border border-white/5 bg-white/[.025] px-3 py-2 text-zinc-400 transition-colors hover:bg-white/[.05]" @click="edit(product)">Редактировать</button><button class="cursor-pointer rounded-[2px] border border-red-500/15 px-3 py-2 text-red-400 transition-colors hover:bg-red-500/[.06]" @click="removeProduct(product.id)">Удалить</button></div></td>
              </tr>
              <tr v-if="!products.length"><td colspan="6" class="p-8 text-center text-zinc-500">Товаров пока нет.</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <section v-else-if="tab === 'leads'">
        <h1 class="mb-5 text-[32px] font-semibold tracking-[-0.03em] text-zinc-100">Заявки с сайта</h1>
        <div class="overflow-x-auto rounded-[2px] border border-white/5">
        <table class="w-full min-w-[900px] text-left text-sm"><thead class="bg-white/[.04] text-zinc-500"><tr><th class="p-4">Дата</th><th class="p-4">Имя</th><th class="p-4">Телефон</th><th class="p-4">Почта</th><th class="p-4">Комментарий</th><th class="p-4">Статус</th><th class="p-4"></th></tr></thead><tbody><tr v-for="lead in leads" :key="lead.id" class="border-t border-white/5"><td class="p-4 text-zinc-500">{{ new Date(lead.created_at).toLocaleString('ru-RU') }}</td><td class="p-4 font-medium">{{ lead.name }}</td><td class="p-4"><a v-if="lead.phone" :href="`tel:${lead.phone}`" class="text-[#756bea]">{{ lead.phone }}</a><span v-else class="text-zinc-600">-</span></td><td class="p-4"><a v-if="lead.email" :href="`mailto:${lead.email}`" class="text-[#756bea]">{{ lead.email }}</a><span v-else class="text-zinc-600">-</span></td><td class="max-w-sm whitespace-pre-wrap p-4 text-zinc-400">{{ lead.comment || '—' }}</td><td class="p-4"><select :value="lead.status" class="rounded-[2px] border border-white/5 bg-[#0b0b11] p-2 text-zinc-300" @change="setStatus(lead.id, ($event.target as HTMLSelectElement).value)"><option value="new">Новая</option><option value="in_progress">В работе</option><option value="done">Готово</option></select></td><td class="p-4"><button class="cursor-pointer text-red-400 transition-colors hover:text-red-300" @click="removeLead(lead.id)">Удалить</button></td></tr><tr v-if="!leads.length"><td colspan="7" class="p-8 text-center text-zinc-500">Заявок пока нет.</td></tr></tbody></table>
        </div>
      </section>

      <section v-else>
        <h1 class="mb-5 text-[32px] font-semibold tracking-[-0.03em] text-zinc-100">Пользователи</h1>

        <form v-if="editingUser" class="admin-card mb-6" @submit.prevent="saveUser">
          <div class="flex items-center justify-between"><h2 class="text-xl font-semibold">Редактировать пользователя</h2><button type="button" class="text-sm text-zinc-400 hover:text-white" @click="closeUserForm">Закрыть</button></div>
          <div class="mt-5 grid gap-4 md:grid-cols-2">
            <label class="block text-sm">Логин<input v-model="userForm.login" class="admin-input mt-2" required /></label>
            <label class="block text-sm">Новый пароль<input v-model="userForm.password" type="password" class="admin-input mt-2" minlength="8" placeholder="Оставьте пустым, чтобы не менять" /></label>
          </div>
          <div v-if="Object.keys(userForm.errors).length" class="mt-4 text-sm text-red-400"><p v-for="error in userForm.errors" :key="error">{{ error }}</p></div>
          <button class="mt-5 cursor-pointer rounded-[2px] bg-[#5548e8] px-4 py-[10px] text-[13px] font-semibold transition-colors hover:bg-[#5d51e9] disabled:opacity-50" :disabled="userForm.processing">Сохранить</button>
        </form>

        <div class="overflow-x-auto rounded-[2px] border border-white/5">
          <table class="w-full min-w-[680px] text-left text-sm">
            <thead class="bg-white/[.04] text-zinc-500"><tr><th class="p-4">Логин</th><th class="p-4">Дата регистрации</th><th class="p-4">Статус</th><th class="p-4 text-right">Действия</th></tr></thead>
            <tbody>
              <tr v-for="user in users" :key="user.id" class="border-t border-white/5">
                <td class="p-4 font-medium">{{ user.login }}</td>
                <td class="p-4 text-zinc-500">{{ new Date(user.created_at).toLocaleString('ru-RU') }}</td>
                <td class="p-4"><span v-if="user.approved_at" class="text-emerald-400">Подтверждён</span><span v-else class="text-amber-400">Ожидает подтверждения</span></td>
                <td class="p-4"><div class="flex justify-end gap-2"><button v-if="!user.approved_at" class="cursor-pointer rounded-[2px] bg-emerald-700 px-3 py-2 font-semibold transition-colors hover:bg-emerald-600" @click="approveUser(user.id)">Подтвердить</button><button class="cursor-pointer rounded-[2px] border border-white/5 bg-white/[.025] px-3 py-2 text-zinc-400 transition-colors hover:bg-white/[.05]" @click="editUser(user)">Редактировать</button><button class="cursor-pointer rounded-[2px] border border-red-500/15 px-3 py-2 text-red-400 transition-colors hover:bg-red-500/[.06]" @click="removeUser(user.id)">Удалить</button></div></td>
              </tr>
              <tr v-if="!users.length"><td colspan="4" class="p-8 text-center text-zinc-500">Пользователей нет.</td></tr>
            </tbody>
          </table>
        </div>
      </section>
      </div>
    </main>
  </div>
</template>
