<template>
  <div class="w-1/2 relative">
    <input @focusout="reset" @focusin="search" @input="search" type="search" class="rounded px-4 py-2 w-full text-slate-600" :placeholder="$t('components.SearchInput.placeholder')">
    <div v-if="state.result" class="absolute bg-white text-black mt-0.5 rounded w-full border border-black shadow-md">
      <NuxtLink @click="resetOnNavigate" v-for="edge in state.result.search.edges" :key="edge.node.id" :to="localePath('/product/' + edge.node.id)" class="flex justify-between mb-2 p-2 group search-result-link">
        <div class="flex flex-col mr-5">
          <span class="text-lg font-semibold group-hover:text-slate-600">{{ edge.node.name }}</span>
          <span class="text-xs text-slate-700 group-hover:text-slate-500">{{ edge.node.studio.name }}</span>
        </div>
        <div class="flex items-center">
          <div class="mr-3 pr-3 border-r" v-if="edge.node.discount > 0">
            <span class="text-red-500 font-semibold text-lg">-{{ edge.node.discount }}%</span>
          </div>
          <div class="flex flex-col mr-3 text-right">
            <span class="font-semibold text-green-600">{{ format(edge.node.currentPrice) }}</span>
            <span class="line-through text-slate-500" v-if="edge.node.discount > 0">{{ format(edge.node.originalPrice) }}</span>
          </div>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
const { locale } = useI18n();

const formatter = Intl.NumberFormat(locale.value, {
  notation: 'standard',
  style: 'currency',
  currency: 'CZK',
  maximumFractionDigits: 0,
})

function format(price) {
  return formatter.format(price);
}

const query = gql`
  query search($query: String!, $lang: String!) {
    search(query: $query, lang: $lang) {
      totalCount
      edges {
        node {
          id
          name
          currentPrice
          originalPrice
          discount
          studio {
            id
            name
          }
        }
      }
    }
  }
`

const state = reactive({result: null});

function search (event) {
  const searchQuery = event.target.value;
  if (searchQuery.length <= 0) {
    return;
  }

  const { result } = useQuery(query, {query: searchQuery, lang: locale.value});
  state.result = result;
}

function resetOnNavigate () {
  state.result = null;
}

function reset (event) {
  if (!event.relatedTarget?.classList?.contains('search-result-link')) {
    state.result = null;
  }
}
</script>
