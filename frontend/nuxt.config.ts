// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    modules: [
        '@nuxtjs/apollo',
        '@nuxtjs/tailwindcss',
    ],
    apollo: {
        clients: {
            default: {
                httpEndpoint: 'http://localhost:3000/api/graphql',
                browserHttpEndpoint: '/api/graphql',
                inMemoryCacheOptions: {
                    typePolicies: {
                        Query: {
                            fields: {
                                products: {
                                    keyArgs: false,
                                    merge(existing = [], incoming) {
                                        return [...existing, ...incoming];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    },
})
