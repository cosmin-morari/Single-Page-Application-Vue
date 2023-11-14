<template>
    <div class="container">
        <input type="text" name="title" class="title" :placeholder="translate.title" v-model="product.title">
        <div style="color:red" class="error title" v-if="error && error.title">{{ error.title[0] }}</div>
        <br>
        <br>
        <input type="text" name="description" class="description" :placeholder="translate.description"
            v-model="product.description">
        <div style="color:red" class="error description" v-if="error && error.description">{{ error.description[0] }}</div>
        <br>
        <br>
        <input type="text" name="price" class="price" :placeholder="translate.price" v-model="product.price">
        <div style="color:red" class="error price" v-if="error && error.price">{{ error.price[0] }}</div>
        <br>
        <br>
        <input type="text" name="category" class="category" :placeholder="translate.category" v-model="product.category">
        <div style="color:red" class="error category" v-if="error && error.category">{{ error.category[0] }}</div>
        <br>
        <br>
        <input type="file" name="file" id="file" class="file" @change="uploadFile" ref="file">
        <div style="color:red" class="error file" v-if="error && error.file"> {{  error.file[0] }}</div>
        <br>
        <br>
        <a href="#/products" class="products">{{ translate.productsPage }}</a>
        <button @click="addProduct()" type="submit" name="save">{{ translate.save }}</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            translate: '',
            productId: window.productEdit ? window.productEdit : '',
            product: '',
            product: {
                title: '',
                description: '',
                price: '',
                category: '',
                file: ''
            },
            error:''
        }
    },
    methods: {
        async addProduct() {
            try {
                const response = await fetch('addProduct', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ title: this.product.title, description: this.product.description, price: this.product.price, category: this.product.category, file: this.file })
                }).then(response => response.json())
                    .then(data => {
                        this.error = data.error;
                    })
            } catch (err) {
                return;
            }
        },
        uploadFile(event) {
            const file = event.target.files[0];
            this.file = file.name;
        }
    },
    created() {
        fetch('/api/translation')
            .then(response => response.json())
            .then(data => {
                this.translate = data
            })
        if (this.productId) {
            fetch(`editProductView/${this.productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.products) {
                        this.product = data.products
                    }
                })
        }
    }
}
</script>