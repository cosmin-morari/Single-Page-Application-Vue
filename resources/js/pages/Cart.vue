<template>
    <h1>{{ translate.cartPage }}</h1>
    <table border="1" v-if="productsCart">
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
                <td>
                    <img :src="`http://localhost/training/Single-Page-Application-Vue/storage/app/public/photos/${item.imageSource}`" alt="">
                </td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price }}</td>
                <td>
                    <input type="number" name="quantity" v-model="item.quantity[key][item.id]">
                    <button type="submit" @click="updateQuantity(item.id)">{{ translate.update }}</button>
                </td>
                <td>
                    <button @click="removeProduct(item.id)">{{ translate.remove }}</button>
                </td>
            </tr>
        </tbody>
    </table>
    <h3 v-else>{{ translate.emptyCart }}</h3>
    <div class="checkOut" v-if="productsCart">
        <input type="text" name="name" :placeholder="translate.name" v-model="contactDetails.name">
        <input type="text" name="contactDetails" :placeholder="translate.contactDetails" v-model="contactDetails.mail">
        <textarea name="comments" cols="20" rows="4" v-model="contactDetails.comments"> </textarea>
        <button type="submit" @click="checkout()">{{ translate.checkout }}</button>
    </div>
    <a href="#/">{{ translate.index }}</a>
</template>

<script>
export default {
    data() {
        return {
            productsCart: '',
            formData: {
                quantityInput: '1'
            },
            contactDetails: {
                name: '',
                mail: '',
                comments: ''
            }
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
                    }
                })
            } catch (err) {
                return;
            }
        },
        async updateQuantity(productId) {
            this.productsCart.forEach(element => {
                if (element.id == productId) {
                    element.quantity.forEach(elemQuantity => {
                        if (elemQuantity.hasOwnProperty(productId)) {
                            this.formData.quantityInput = elemQuantity[productId]
                        }
                    })
                }
            });
            try {
                const response = await fetch(`updateQuantity/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData.quantityInput)
                })
            } catch (err) {
                return;
            }
        },
        async checkout() {
            try {
                const response = await fetch('checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.contactDetails)
                })
                window.location.hash = '/'
            }
            catch (error) {
                return;
            }
        }
    },
    created() {
        fetch('cart')
            .then(response => response.json())
            .then(data => {
                this.productsCart = data;
            })
    }
}

</script>