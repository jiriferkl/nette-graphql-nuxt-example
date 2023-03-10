// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    modules: [
        '@nuxtjs/apollo',
        '@nuxtjs/tailwindcss',
        '@nuxtjs/i18n',
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
    i18n: {
        locales: [
            {
                code: 'cs',
                name: 'Čeština',
            },
            {
                code: 'en',
                name: 'English',
            },
        ],
        defaultLocale: 'cs',
        vueI18n: {
            messages: {
                cs: {
                    app: {
                        nav: {
                            homepage: 'Domů',
                            product: 'Produkty',
                        }
                    },
                    components: {
                        LangSwitcher: {
                            chooseLang: 'Vybrat jazyk'
                        },
                        SearchInput: {
                            placeholder: 'Hledat',
                        },
                    },
                    pages: {
                        product: {
                            index: {
                                loadMore: 'Načíst další'
                            }
                        }
                    }
                },
                en: {
                    app: {
                        nav: {
                            homepage: 'Home',
                            product: 'Products',
                        }
                    },
                    components: {
                        LangSwitcher: {
                            chooseLang: 'Change language'
                        },
                        SearchInput: {
                            placeholder: 'Search',
                        },
                    },
                    pages: {
                        product: {
                            index: {
                                loadMore: 'Load more'
                            }
                        }
                    }
                },
            }
        }
    }
})
