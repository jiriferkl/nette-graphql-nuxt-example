<template>
  <div class="flex flex-col min-h-screen">
    <header>
      <div class="bg-black text-neutral-400">
        <div class="container mx-auto flex justify-between items-center py-8 px-2">
          <NuxtLink :to="localePath('/')">Eshop</NuxtLink>
          <SearchInput />
          <LangSwitcher />
        </div>
      </div>
      <div class="bg-neutral-800 text-white">
        <div class="container mx-auto py-4 px-2">
          <nav class="uppercase h-6">
            <NuxtLink class="mr-5 hover:border-b hover:translate-y-0.5 hover:scale-105 transition ease-in-out duration-75 inline-block" :to="localePath('/')">{{ $t('app.nav.homepage') }}</NuxtLink>
            <NuxtLink class="mr-5 hover:border-b hover:translate-y-0.5 hover:scale-105 transition ease-in-out duration-75 inline-block" :to="localePath('/product')">{{ $t('app.nav.product') }}</NuxtLink>
          </nav>
        </div>
      </div>
    </header>
    <article class="container mx-auto grow pt-5 px-2">
      <NuxtPage />
    </article>
    <footer class="bg-neutral-800 text-white p-20"></footer>
  </div>
</template>
