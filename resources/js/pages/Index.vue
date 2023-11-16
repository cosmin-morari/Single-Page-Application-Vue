<template>
    <h1>{{ translate.index }}</h1>
    <table border="1">
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
        <tbody v-for="item in productsIndex" :key="item.id">
            <tr>
                <td>{{ item.id }} </td>
                <td>
                    <img :src="`http://localhost/training/Single-Page-Application-Vue/public/storage/photos/${item.imageSource}`"
                        alt="">
                </td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price }}</td>
                <td>
                    <button @click="addToCart(item.id)">{{ translate.addProduct }}</button>
                    <button @click="detailsProduct(item.id)">{{ translate.actionViewProduct }}</button>
                </td>
            </tr>
        </tbody>
    </table>
    <a href="#/cart">{{ translate.cart }}</a>
</template>

<script>

export default {
    data() {
        return {
            productsIndex: ''
        }
    },
    methods: {
        async addToCart(productId) {
            try {
                const response = await fetch(`addToCart/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
            } catch (err) {
                return;
            }
        },
        async detailsProduct(product) {
            try {
                await fetch(`detailsProduct/${product}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                }).then(response => {
                    if (response.status !== 200) {
                        throw new Error('Sorry, an error occurred while processing your request.');
                    }
                    return response.json();
                }).then(data => {
                    console.log(data)
                    window.data = data;
                    window.location.hash = '#/detailsProduct';
                })
            }
            catch (err) {
                console.error(err)
            }
        }
    },
    created() {
        fetch('index')
            .then(response => response.json())
            .then(data => {
                this.productsIndex = data
            })
    }
}

</script>