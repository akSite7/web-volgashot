<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Footer from '@/components/Footer.vue'
import Header from '@/components/Header.vue'

type Product = { id: number; name: string; type: 'shot' | 'buckshot'; description: string | null; price_per_kg: string; photo_url: string | null }
const props = defineProps<{ products: Product[]; seo: { canonical: string; description: string } }>()
const formatPrice = (price: string) => Math.round(Number(price)).toLocaleString('ru-RU')
const activeCategory = ref<'shot' | 'buckshot'>('shot')
const activeProducts = computed(() => props.products.filter((product) => product.type === activeCategory.value))
const socialImage = computed(() => {
  const photo = props.products.find((product) => product.photo_url)?.photo_url
  return photo ? new URL(photo, props.seo.canonical).href : null
})
const structuredData = computed(() => JSON.stringify({
  '@context': 'https://schema.org',
  '@type': 'Organization',
  name: 'VOLGASHOT',
  url: props.seo.canonical,
  description: props.seo.description,
  makesOffer: props.products.map((product) => ({
    '@type': 'Offer',
    priceCurrency: 'RUB',
    price: Math.round(Number(product.price_per_kg)),
    itemOffered: {
      '@type': 'Product',
      name: product.name,
      description: product.description ?? undefined,
      image: product.photo_url ? new URL(product.photo_url, props.seo.canonical).href : undefined,
    },
  })),
}))

const leadForm = useForm({ name: '', phone: '', email: '', comment: '' })
const sent = ref(false)

function formatPhone(event: Event) {
  const input = event.target as HTMLInputElement
  let digits = input.value.replace(/\D/g, '')

  if (digits.startsWith('7') || digits.startsWith('8')) digits = digits.slice(1)
  digits = digits.slice(0, 10)

  let value = '+7'
  if (digits.length > 0) value += ` (${digits.slice(0, 3)}`
  if (digits.length >= 3) value += ')'
  if (digits.length > 3) value += ` ${digits.slice(3, 6)}`
  if (digits.length > 6) value += `-${digits.slice(6, 8)}`
  if (digits.length > 8) value += `-${digits.slice(8, 10)}`

  input.value = value
  leadForm.phone = value
}

function startPhone() {
  if (!leadForm.phone) leadForm.phone = '+7'
}

function clearEmptyPhone() {
  if (leadForm.phone === '+7') leadForm.phone = ''
}

function submitForm() {
  leadForm.post('/zayavki', {
    preserveScroll: true,
    onSuccess: () => { sent.value = true; leadForm.reset() },
  })
}
</script>

<template>
  <Head>
    <title>Охотничья дробь и картечь от производителя</title>
    <meta name="description" :content="seo.description" />
    <link rel="canonical" :href="seo.canonical" />
    <meta property="og:locale" content="ru_RU" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="VOLGASHOT" />
    <meta property="og:title" content="Охотничья дробь и картечь от производителя — VOLGASHOT" />
    <meta property="og:description" :content="seo.description" />
    <meta property="og:url" :content="seo.canonical" />
    <meta v-if="socialImage" property="og:image" :content="socialImage" />
    <meta v-if="socialImage" property="og:image:alt" content="Продукция VOLGASHOT" />
    <meta name="twitter:card" :content="socialImage ? 'summary_large_image' : 'summary'" />
    <meta name="twitter:title" content="Охотничья дробь и картечь от производителя — VOLGASHOT" />
    <meta name="twitter:description" :content="seo.description" />
    <meta v-if="socialImage" name="twitter:image" :content="socialImage" />
    <component :is="'script'" type="application/ld+json">{{ structuredData }}</component>
  </Head>
  <div class="min-h-screen bg-[#0d0d13] text-white">
    <Header @select-category="activeCategory = $event" />

    <main>
      <section class="relative overflow-hidden">
        <div class="mx-auto grid min-h-[770px] max-w-[1272px] gap-0 px-5 lg:grid-cols-[650px_622px] xl:px-0">
          <div class="relative z-10 pt-[125px]">
            <div class="mb-[27px] inline-flex rounded-[2px] border border-white/5 bg-white/[0.035] px-3 py-2 text-xs text-zinc-400">
              🔨 У нас лучшее качество
            </div>

            <h1 class="max-w-[650px] text-[44px] font-medium leading-[.98] tracking-[-0.035em] text-zinc-100 sm:text-[54px] lg:text-[58px]">
              Производство и продажа оружейной Дроби, Картечи свинцовой охотничьей.
            </h1>

            <p class="mt-[32px] max-w-[545px] text-[15px] leading-[23px] text-zinc-500">
                Наше производство специализируется на продаже оружейной дроби высокого качества для различных видов стрелкового оружия. Мы предлагаем широкий ассортимент дроби и картечи от производителя, что позволяет нашим клиентам выбрать подходящий вариант для своих нужд.
            </p>

            <div class="mt-[45px] flex flex-wrap gap-4">
              <a
                href="#catalog"
                class="cursor-pointer rounded-[2px] bg-[#5548e8] px-4 py-[10px] text-[13px] font-semibold transition-colors hover:bg-[#5d51e9]"
              >
                Посмотреть ассортимент
              </a>
              <a
                target="_blank"
                href="https://www.wildberries.ru/catalog/721329523/feedbacks?imtId=737271835&size=1015294730"
                class="rounded-[2px] border border-white/5 bg-white/[0.035] px-4 py-[10px] text-[13px] font-semibold text-zinc-300 transition hover:bg-white/[0.08]"
              >
                Отзывы
              </a>
            </div>
          </div>

          <div class="relative hidden min-h-[770px] lg:block rotate-190">
            <img
              src="/shotgun.svg"
              alt="Силуэт охотничьего ружья"
              class="absolute -top-[5px] left-[60px] h-[770px] w-[650px] -scale-x-100 object-contain"
            />
          </div>
        </div>
      </section>

      <section id="catalog" class="scroll-mt-[72px] pt-[30px] pb-[72px]">
        <div class="mx-auto max-w-[1272px] px-5 xl:px-0">
          <div class="mb-[28px]">
            <div class="mb-5 inline-flex rounded-[2px] border border-white/5 bg-white/[0.035] px-3 py-2 text-xs text-zinc-400">
              🛒 Наш ассортимент
            </div>

            <nav aria-label="Категории товаров" class="mb-5 flex items-center gap-4 text-xs">
              <button type="button" :aria-pressed="activeCategory === 'shot'" :class="activeCategory === 'shot' ? 'cursor-pointer text-indigo-400 underline decoration-indigo-400/30 underline-offset-4' : 'cursor-pointer text-zinc-500 hover:text-zinc-300'" @click="activeCategory = 'shot'">Дробь</button>
              <span class="text-zinc-600">/</span>
              <button type="button" :aria-pressed="activeCategory === 'buckshot'" :class="activeCategory === 'buckshot' ? 'cursor-pointer text-indigo-400 underline decoration-indigo-400/30 underline-offset-4' : 'cursor-pointer text-zinc-500 hover:text-zinc-300'" @click="activeCategory = 'buckshot'">Картечь</button>
            </nav>

            <h2 class="text-[46px] font-semibold leading-none tracking-[-0.03em] text-zinc-100">{{ activeCategory === 'shot' ? 'Дробь' : 'Картечь' }}</h2>
            <p class="mt-6 text-[13px] leading-5 text-zinc-500">
              {{ activeCategory === 'shot' ? 'Размеры соответствуют ГОСТ 7837-76 и имеют шаг 0,25 мм' : 'Размеры соответствуют ГОСТ 7837-76' }}<br />
              Возможность заказа от 2 килограмм.
            </p>
          </div>

          <div class="grid gap-x-[52px] gap-y-10 sm:grid-cols-2 xl:grid-cols-4">
            <article v-for="product in activeProducts" :key="product.id" class="group">
              <div class="overflow-hidden rounded-lg border border-white/10 bg-white/[.04]">
                <img v-if="product.photo_url" :src="product.photo_url" :alt="product.name" class="block h-auto w-full " />
                <div v-else class="grid aspect-square w-full place-items-center text-sm text-zinc-600">Фото скоро появится</div>
              </div>
              <h3 class="mt-3 text-[15px] font-medium text-zinc-100">{{ product.name }}</h3>
              <p class="mt-1 text-[11px] leading-4 text-zinc-500">{{ product.description }}</p>
              <div class="mt-1 text-[15px] text-zinc-400">{{ formatPrice(product.price_per_kg) }} ₽ <span class="text-[11px]">за 1 кг</span></div>
              <a href="#contacts" class="mt-3 inline-flex cursor-pointer rounded-[2px] bg-[#5548e8] px-3 py-2 text-[12px] font-semibold text-white transition-colors hover:bg-[#5d51e9]">
                Заказать
              </a>
            </article>
          </div>
          <p v-if="!activeProducts.length" class="text-sm text-zinc-500">В этой категории товары пока не добавлены.</p>
        </div>
      </section>

      <section id="contacts" class="scroll-mt-[72px] py-24">
        <div class="mx-auto grid max-w-[1272px] items-center gap-16 px-5 lg:grid-cols-2 xl:px-0">
          <div>
            <div class="mb-5 inline-flex rounded-[2px] border border-white/10 bg-white/[0.04] px-3 py-2 text-xs text-zinc-400">
              📞 Заказывайте сегодня
            </div>
            <h2 class="max-w-lg text-4xl font-semibold tracking-tight sm:text-5xl">
              Хотите сделать заказ?
            </h2>
            <p class="mt-6 max-w-lg leading-7 text-zinc-500">
              Оставьте свои контактные данные для связи и обязательно укажите в комментарии номера дроби или картечи, которые вас интересуют, их вес, а также город или населенный пункт, куда необходимо организовать доставку. Мы перезвоним в ближайшее время для уточнения деталей и расчета стоимости доставки. Благодарим за проявленный интерес к нашему ассортименту, ждем вашей заявки!
            </p>
          </div>

          <form @submit.prevent="submitForm" class="rounded-[3px] border border-white/5 bg-white/[0.025] p-6 sm:p-8">
            <h3 class="mb-6 text-xl font-medium text-zinc-300">Заполните форму</h3>

            <div class="space-y-3">
              <input v-model="leadForm.name" class="field" type="text" placeholder="Ваше имя" required />
              <div class="grid items-center gap-3 sm:grid-cols-[1fr_auto_1fr]">
                <input v-model="leadForm.phone" class="field" type="tel" inputmode="tel" maxlength="18" placeholder="+7 (___) ___-__-__" @focus="startPhone" @blur="clearEmptyPhone" @input="formatPhone" />
                <span class="text-center text-[11px] font-semibold tracking-[0.12em] text-zinc-600">ИЛИ</span>
                <input v-model="leadForm.email" class="field" type="email" placeholder="Почта" />
              </div>
              <textarea v-model="leadForm.comment" class="field min-h-32 resize-none" placeholder="Комментарий"></textarea>
            </div>

            <div v-if="Object.keys(leadForm.errors).length" class="mt-3 text-sm text-red-400">
              <p v-for="error in leadForm.errors" :key="error">{{ error }}</p>
            </div>

            <p class="mt-3 text-[11px] leading-5 text-zinc-600">
              Нажимая кнопку, вы соглашаетесь на обработку персональных данных.
            </p>

            <button :disabled="leadForm.processing" class="mt-4 cursor-pointer rounded-[2px] bg-[#5548e8] px-4 py-[10px] text-[13px] font-semibold transition-colors hover:bg-[#5d51e9] disabled:cursor-not-allowed disabled:opacity-50">
              Отправить
            </button>

            <p v-if="sent" class="mt-4 text-sm text-emerald-400">
              Заявка отправлена. Мы скоро свяжемся с вами.
            </p>
          </form>
        </div>
      </section>

      <section id="delivery" class="py-24">
        <div class="mx-auto max-w-[1272px] px-5 xl:px-0">
          <div class="mb-8">
            <div class="mb-5 inline-flex rounded-[2px] border border-white/10 bg-white/[0.04] px-3 py-2 text-xs text-zinc-400">
             ❗ Важная информация
            </div>
            <h2 class="max-w-3xl text-3xl font-semibold tracking-tight sm:text-4xl">
              Доставку осуществляем транспортными компаниями по согласованию.
            </h2>
            <p class="mt-4 text-sm text-zinc-500">Принимаем заказы от 2кг. <br>Оптовые продажи обсуждаются отдельно.</p>
          </div>

          <div class="relative overflow-hidden rounded-[2px]  bg-[#d6e4d0]">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A1fbcb60904dd405a63d45bac9d7ab781319bdff014a39996025eff759169a180&amp;source=constructor" width="1272" height="340" frameborder="0"></iframe>
          </div>

         
        </div>
      </section>
    </main>

    <Footer @select-category="activeCategory = $event" />
  </div>
</template>

<style scoped>
.field {
  width: 100%;
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 2px;
  background: #0b0b11;
  padding: .9rem 1rem;
  font-size: .875rem;
  color: white;
  outline: none;
  transition: .2s ease;
}

.field::placeholder {
  color: #52525b;
}

.field:focus {
  border-color: rgba(124,58,237,.75);
}
</style>
