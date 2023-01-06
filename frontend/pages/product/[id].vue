<template>
  <div>
    <div v-if="result && result.product">
      <div class="flex flex-col md:flex-row">
        <div class="md:w-1/2"></div>
        <div class="md:w-1/2 md:pl-5">
          <h1 class="text-2xl">{{ result.product.name }}</h1>
          <h2 class="text-lg text-slate-600">{{ result.product.shortDescription }}</h2>
          <div>
            <span class="text-2xl mr-5">{{ format(result.product.currentPrice) }}</span>
            <span class="text-lg text-slate-600 line-through">{{ format(result.product.originalPrice) }}</span>
          </div>
        </div>
      </div>
      <div>
        {{ result.product.longDescription }}
      </div>
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

const route = useRoute()

const query = gql`
  query product($id: ID!, $lang: Language!) {
    product(id: $id) {
      id
      name
      shortDescription(lang: $lang)
      longDescription(lang: $lang)
      currentPrice
      originalPrice
    }
  }
`

const { result } = useQuery(query, {id: route.params.id, lang: locale.value});
</script>
