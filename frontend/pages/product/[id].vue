<template>
  <div>
    <div v-if="result && result.product">
      <div class="flex">
        <div class="w-1/2">
        </div>
        <div class="w-1/2 pl-5">
          <h1 class="text-2xl">{{ result.product.name }}</h1>
          <h2 class="text-lg text-slate-600">{{ result.product.shortDescription }}</h2>
          <div>
            <span class="text-2xl mr-5">{{ result.product.currentPrice }}</span>
            <span class="text-lg text-slate-600 line-through">{{ result.product.originalPrice }}</span>
          </div>
        </div>
      </div>
      <div>
        {{ result.product.fullDescription }}
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
      name
      shortDescription
      fullDescription
      currentPrice
      originalPrice
    }
  }
`

const { result } = useQuery(query, {id: route.params.id});
</script>
