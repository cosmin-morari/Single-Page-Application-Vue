<script>
import Index from './pages/Index.vue';
import Cart from './pages/Cart.vue';
import Login from './pages/Login.vue';
import Products from './pages/Products.vue';
import Product from './pages/Product.vue';


const routes = {
    '/': Index,
    '/cart': Cart,
    '/login' : Login,
    '/products' : Products,
    '/product' : Product
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
    <component :is="currentView" />
</template>