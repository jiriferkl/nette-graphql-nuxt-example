export default defineEventHandler((event) => {
    return proxyRequest(event, 'http://nginx');
})
