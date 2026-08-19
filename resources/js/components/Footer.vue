<script setup lang="ts">
defineEmits<{
  selectCategory: [category: 'shot' | 'buckshot']
}>()

const currentYear = new Date().getFullYear()
</script>

<template>
  <footer class="border-t border-white/10 bg-[#0d0d13]">
    <div class="mx-auto grid max-w-[1272px] gap-12 px-5 py-9 sm:grid-cols-2 lg:grid-cols-[1.15fr_1fr_.75fr] xl:px-0">
      <div>
        <div class="text-[22px] font-semibold tracking-[-0.02em] text-white">VOLGASHOT</div>
        <p class="mt-6 max-w-[420px] text-[15px] leading-[30px] text-zinc-500">Мы надеемся что после приобретения нашей продукции у вас останутся только положительные эмоции. Мы работаем над тем, чтобы наши товары были высокого качества. В случае каких-либо проблем или вопросов, пожалуйста, не стесняйтесь обращаться к нам.</p>
        <div class="mt-8 text-sm text-zinc-500">© {{ currentYear }} VOLGASHOT</div>
      </div>
      <div>
        <div class="text-[17px] font-semibold text-zinc-100">Основные разделы</div>
        <nav class="mt-6 flex flex-col items-start gap-4 text-[15px] text-zinc-500">
          <a href="#catalog" class="transition hover:text-white" @click="$emit('selectCategory', 'shot')">Дробь</a>
          <a href="#catalog" class="transition hover:text-white" @click="$emit('selectCategory', 'buckshot')">Картечь</a>
          <a href="#contacts" class="transition hover:text-white">Контакты</a>
        </nav>
      </div>
      <div>
        <div class="text-[17px] font-semibold text-zinc-100">Дополнительно</div>
        <nav class="mt-6 flex flex-col items-start gap-4 text-[15px] text-zinc-500">
          <a target="_blank" href="https://ozon.ru/s/3844625" class="transition hover:text-white">OZON</a>
          <a target="_blank" href="https://www.wildberries.ru/seller/250086522" class="transition hover:text-white">Wildberries</a>
          <a target="_blank" href="https://market.yandex.ru/cc/Ak8cko" class="transition hover:text-white">Яндекс Маркет</a>
        </nav>
      </div>
    </div>
  </footer>
</template>
