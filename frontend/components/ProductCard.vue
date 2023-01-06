<template>
  <NuxtLink :to="localePath('/product/' + product.id)" class="inline-block border shadow p-5">
    {{ product.name }}
  </NuxtLink>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true,
  },
})
</script>
