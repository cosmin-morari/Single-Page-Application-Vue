<template>
    <a href="#/orders">{{ translate.ordersPage }}</a>
    <table border="1" v-if="order">
        <thead>
            <tr>
                <td>{{ translate.image }}</td>
                <td>{{ translate.name }}</td>
                <td>{{ translate.yourQuantity }}</td>
                <td>{{ translate.totalPrice }}</td>
            </tr>
        </thead>
        <tbody v-for="item in order.products" :key="item.id">
            <tr>
                <td><img :src="`http://localhost/training/Single-Page-Application-Vue/public/storage/photos/${item.imageSource}`"
                        alt=""></td>
                <td>{{ item.title }}</td>
                <td>{{ item.pivot.quantity }}</td>
                <td>{{ item.pivot.checkout_price * item.pivot.quantity }}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    data() {
        return {
            orderId: window.order,
            translate: '',
            order: ''
        }
    },
    created() {
        fetch('/api/translation')
            .then(response => response.json())
            .then(data => {
                this.translate = data;
            })
        fetch(`order/${this.orderId}`)
            .then(response => response.json())
            .then(data => {
                this.order = data
                console.log(data)
            })
    }
}
</script>
