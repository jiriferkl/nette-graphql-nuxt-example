<template>
  <div class="relative">
    <button @click="state.show = !state.show" :title="$t('components.LangSwitcher.chooseLang')" class="uppercase underline underline-offset-2">{{ locale }}</button>
    <ul v-show="state.show" class="absolute bg-white z-10 pl-2 pr-6 py-1 border border-slate-600 right-0 text-black">
      <li v-for="locale in locales" :key="locale.code">
        <NuxtLink :to="switchLocalePath(locale.code)" @click="state.show = false">{{ locale.name }}</NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup>
const {locale, locales} = useI18n();
const switchLocalePath = useSwitchLocalePath()
const state = reactive({show: false})
</script>
