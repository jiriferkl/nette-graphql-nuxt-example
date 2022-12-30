<template>
  <div>
    <div v-for="product in data.products" :key="product.id">{{ product.title }}</div>
  </div>
</template>

<script setup>
const query = gql`
  query products {
    products {
      id
      title
    }
  }
`

const { data } = await useAsyncQuery(query);
</script>
