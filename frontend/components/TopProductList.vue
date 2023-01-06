<template>
  <div>
    <div v-if="result && result.products">
      <div v-for="product in result.products.edges" :key="product.node.id">
        <ProductCard :product="product.node"/>
      </div>
    </div>
    <div v-else>Loading..</div>
  </div>
</template>

<script setup>
const query = gql`
  query products($pagination: Pagination!) {
    products(pagination: $pagination) {
      edges {
        node {
          id
          name
        }
      }
    }
  }
`

const { result } = useQuery(query, {
  pagination: {
    first: 10,
  }
});
</script>
