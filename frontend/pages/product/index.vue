<template>
  <div>
    <div v-if="result && result.products">
      <NuxtLink v-for="product in result.products.edges" :key="product.node.id" :to="'/product/' + product.node.id">
        <div>
          {{ product.node.id }} {{ product.node.title }}
        </div>
      </NuxtLink>
      <div>
        <button v-if="result.products.pageInfo.hasNextPage && !loading" @click="loadMore">{{ $t('pages.product.index.loadMore') }}</button>
        <button v-else disabled>{{ $t('pages.product.index.loadMore') }}</button>
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
          title
        }
      }
      pageInfo {
        endCursor
        hasNextPage
      }
    }
  }
`

const variables = {
  pagination: {
    first: 10,
    after: null,
  }
};

const { result, loading, fetchMore } = useQuery(query, variables);

function loadMore() {
  variables.pagination.after = result.value.products.pageInfo.endCursor;
  fetchMore({
    variables: variables,
    updateQuery: (previousResult, { fetchMoreResult }) => {
      const newEdges = fetchMoreResult.products.edges;
      const pageInfo = fetchMoreResult.products.pageInfo;

      return newEdges.length ? {
        ...previousResult,
        products: {
          ...previousResult.products,
          edges: [
            ...previousResult.products.edges,
            ...newEdges,
          ],
          pageInfo,
        }
      } : previousResult;
    }
  });
}
</script>
