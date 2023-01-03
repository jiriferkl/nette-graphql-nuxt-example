<template>
  <div>
    <div v-if="result && result.product" class="flex">
      <div class="w-1/2">
      </div>
      <div class="w-1/2 pl-5">
        <h1 class="text-2xl">{{ result.product.title }}</h1>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()

const query = gql`
  query product($id: ID!) {
    product(id: $id) {
      id
      title
    }
  }
`

const { result } = useQuery(query, {id: route.params.id});
</script>
