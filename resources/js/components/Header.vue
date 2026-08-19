<script setup lang="ts">
defineEmits<{
  selectCategory: [category: 'shot' | 'buckshot']
}>()
</script>

<template>
  <header class="sticky top-0 z-50 border-b border-white/8 bg-[#0d0d13]">
    <div class="mx-auto flex h-[72px] max-w-[1272px] items-center justify-between px-5 xl:px-0">
      <a href="#" class="text-[13px] font-semibold text-zinc-500">VOLGASHOT</a>
      <nav class="mr-auto ml-8 hidden items-center gap-6 text-[13px] text-zinc-200 md:flex">
        <a href="#catalog" class="transition hover:text-white" @click="$emit('selectCategory', 'shot')">Дробь</a>
        <a href="#catalog" class="transition hover:text-white" @click="$emit('selectCategory', 'buckshot')">Картечь</a>
        <a href="#contacts" class="transition hover:text-white">Контакты</a>
      </nav>
      <div class="flex gap-4">
        <a target="_blank" href="https://ozon.ru/s/3844625" class="hidden rounded-[2px] border border-white/5 bg-white/[0.035] px-4 py-2 text-[13px] font-semibold text-zinc-300 transition hover:bg-white/10 sm:block">Ozon</a>
        <a target="_blank" href="https://www.wildberries.ru/seller/250086522" class="rounded-[2px] border border-white/5 bg-white/[0.035] px-4 py-2 text-[13px] font-semibold text-zinc-300 transition hover:bg-white/10">Wildberries</a>
        <a target="_blank" href="https://market.yandex.ru/cc/Ak8cko" class="hidden rounded-[2px] border border-white/5 bg-white/[0.035] px-4 py-2 text-[13px] font-semibold text-zinc-300 transition hover:bg-white/10 sm:block">Яндекс Маркет</a>
      </div>
    </div>
  </header>
</template>
