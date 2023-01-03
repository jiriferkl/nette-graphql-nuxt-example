<template>
  <div>
    <header class="flex justify-between py-3 px-5">
      <nav>
        <NuxtLink class="mr-2" to="/">{{ $t('app.nav.homepage') }}</NuxtLink>
        <NuxtLink class="mr-2" to="/product">{{ $t('app.nav.product') }}</NuxtLink>
      </nav>
      <LangSwitcher />
    </header>
    <article class="container mx-auto">
      <NuxtPage />
    </article>
  </div>
</template>
