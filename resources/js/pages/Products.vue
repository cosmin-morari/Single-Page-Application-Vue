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
        <tbody v-for="item in products" :key="item.id">
            <tr>
                <td>{{ item.id }} </td>
                <td><img :src="`http://localhost/training/Single-Page-Application-Vue/storage/app/public/photos/${item.imageSource}`"
                        alt=""></td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price }}</td>
                <td><button @click="addToCart(item.id)">{{ translate.addProduct }}</button></td>
            </tr>

        </tbody>
    </table>
</template>

<script>
export default{
    data(){
        return{
            products: '',
            translate: ''
        }
    },
    created(){
        fetch('products')
            .then(response => response.json())
            .then(data => {
                this.products = data
            }),
            fetch('/api/translation')
            .then(response => response.json())
            .then(data => {
                this.translate = data
            })
    }
   
}
</script>
