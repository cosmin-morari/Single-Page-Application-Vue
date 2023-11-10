<template>
    <h1>{{ translate.cartPage }}</h1>
    <table border="1">
        <thead>
            <tr>
                <td>{{ translate.id }}</td>
                <td>{{ translate.image }}</td>
                <td>{{ translate.title }}</td>
                <td>{{ translate.description }}</td>
                <td>{{ translate.price }}</td>
                <td>{{ translate.yourQuantity }}</td>
                <td>{{ translate.action }}</td>
            </tr>
        </thead>
        <tbody v-for="item, key in productsCart" :key="item.id">
            <tr>
                <td>{{ item.id }} </td>
                <td><img :src="`http://localhost/training/Single-Page-Application-Vue/storage/app/public/photos/${item.imageSource}`"
                        alt=""></td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price }}</td>
                <td> <input type="number" v-model="item.quantity[key][item.id]"></td>
                <td>
                    <button @click="removeProduct(item.id)">{{ translate.remove }}</button> 
                    <button @click="updateQuantity(item.id)">{{ translate.update }}</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>

export default {
    data() {
        return {
            productsCart: '',
            translate: '',
        }
    },
    methods: {
        async removeProduct(productId) {
            try {
                const response = await fetch(`deleteProductCart/${productId}`, {
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
        async updateQuantity(productId) {
            try {
                console.log(item.quantity[productId])
                const response = await fetch(`updateQuantity/${productId}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
            } catch (err) {
                return;
            }
        }
    },
    created() {
        fetch('cart')
            .then(response => response.json())
            .then(data => {
                this.productsCart = data;
                console.log(data)
            })

        fetch('/api/translation')
            .then(response => response.json())
            .then(data => {
                this.translate = data
            })
    }
}

</script>