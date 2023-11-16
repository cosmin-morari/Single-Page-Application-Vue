<template>
    <h1>{{ translate.productsRecommended }}</h1>
    <table v-if="data.product" border="1">
        <thead>
            <tr>
                <td>{{ translate.title }}</td>
                <td>{{ translate.category }}</td>
                <td>{{ translate.image }}</td>
                <td>{{ translate.description }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ data.product.title }}</td>
                <td>{{ data.product.category }}</td>
                <td>
                    <img :src="`http://localhost/training/Single-Page-Application-Vue/public/storage/photos/${data.product.imageSource}`"
                        alt="">
                </td>
                <td>{{ data.product.description }}</td>
            </tr>
        </tbody>
    </table>
    <a href="#/">{{ translate.back }}</a>
    <h3>{{ translate.productsRecommended }}</h3>
    <table v-if="data.sameCategoryproducts" border="1">
        <thead>
            <tr>
                <td>{{ translate.image }}</td>
                <td>{{ translate.title }}</td>
                <td>{{ translate.description }}</td>
                <td>{{ translate.price }}</td>
            </tr>
        </thead>
        <tbody v-for="product in data.sameCategoryproducts" :key="product.id">
            <tr>
                <td>
                    <img :src="`http://localhost/training/Single-Page-Application-Vue/public/storage/photos/${product.imageSource}`"
                        alt="">
                </td>
                <td>{{ product.title }}</td>
                <td>{{ product.description }}</td>
                <td>{{ product.price }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data() {
        return {
            data: window.data
        }
    }
}
</script>