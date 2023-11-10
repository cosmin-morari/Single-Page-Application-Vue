<script>
import Index from './pages/index.vue'
import Cart from './pages/Cart.vue'


const routes = {
    '/': Index,
    '/cart': Cart
}

export default {
    data() {
        return {
            currentPath: window.location.hash
        }
    },
    computed: {
        currentView() {
            return routes[this.currentPath.slice(1) || '/']
        }
    },
    mounted() {
        window.addEventListener('hashchange', () => {
            this.currentPath = window.location.hash
        })
    }
}
</script>

<template>
    <a href="#/">Index</a> |
    <a href="#/cart">Cart</a> |
    <component :is="currentView" />
</template>