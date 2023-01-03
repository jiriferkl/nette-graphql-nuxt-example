<template>
  <div>
    <button @click="state.show = !state.show">{{ $t('components.LangSwitcher.chooseLang') }}</button>
    <ul v-show="state.show" class="absolute bg-white z-10 pl-2 pr-6 py-1 border border-slate-600">
      <li v-for="locale in locales" :key="locale.code">
        <NuxtLink :to="switchLocalePath(locale.code)" @click="state.show = false">{{ locale.name }}</NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup>
const {locales} = useI18n();
const switchLocalePath = useSwitchLocalePath()
const state = reactive({show: false})
</script>
