<template>
    <h1>{{ translate.productsPage }}</h1>
    <div class="navbar" style="display:flex; width:100%; justify-content:space-around;">
        <a href="#/product">{{ translate.addProduct }}</a>
        <a href="#/orders">{{ translate.ordersPage }}</a>
        <button @click="logoutAdmin()">{{ translate.logout }}</button>
    </div>
    <br>
    <br>
    <table border="1" v-if="products">
        <thead>
            <tr>
                <td>{{ translate.id }}</td>
                <td>{{ translate.image }}</td>
                <td>{{ translate.title }}</td>
                <td>{{ translate.description }}</td>
                <td>{{ translate.price }}</td>
                <td>{{ translate.action }}</td>
            </tr>
        </thead>
        <tbody v-for="item in products" :key="item.id">
            <tr>
                <td>{{ item.id }} </td>
                <td><img :src="`http://localhost/training/Single-Page-Application-Vue/public/storage/photos/${item.imageSource}`"
                        alt=""></td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price }}</td>
                <td>
                    <button @click="editProduct(item.id)">{{ translate.edit }}</button>
                    <button @click="deleteProduct(item.id)">{{ translate.delete }}</button>
                </td>
            </tr>
        </tbody>
    </table>
    <h1 v-else>{{ translate.notProductsInDB }}</h1>
</template>

<script>
export default {
    data() {
        return {
            products: '',
            translate: ''
        }
    },
    methods: {
        async logoutAdmin() {
            try {
                const response = await fetch('logoutAdmin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                window.location.hash = '/login';
            } catch (err) {
                return;
            }
        },
        async deleteProduct(product) {
            try {
                const response = await fetch(`deleteProduct/${product}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
            } catch (err) {
                return;
            }
        },
        editProduct(product) {
            window.location.hash = '/product'
            window.productEdit = product
        }
    },
    created() {
        fetch('products', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => response.json())
            .then(data => {
                if (data.error) {
                    window.location.hash = '/login';
                } else {
                    this.products = data;
                    window.productEdit = '';
                }
            }),
            fetch('/api/translation')
                .then(response => response.json())
                .then(data => {
                    this.translate = data;
                })
    }
}
</script>

