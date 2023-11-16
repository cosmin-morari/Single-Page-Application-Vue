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
        <div style="color:red" class="error file" v-if="error && error.file"> {{ error.file[0] }}</div>
        <br>
        <br>
        <a href="#/products" class="products">{{ translate.productsPage }}</a>
        <button v-if="productId" @click="update(productId)">{{ translate.update }}</button>
        <button v-else @click="addProduct()" type="submit" name="save">{{ translate.save }}</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            productId: window.productEdit ? window.productEdit : '',
            product: {
                title: '',
                description: '',
                price: '',
                category: '',
                file: ''
            },
            error: '',
        }
    },
    methods: {
        addProductComponent() {
            try {
                fetch('addProduct')
                    .then(response => {
                        if (response.status === 401) {
                            window.location.hash = '#/products'
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {

                            throw new Error(data.error);
                        }
                    })
            } catch (err) {
                console.log(err);
            }
        },
        editProductComponent() {
            if (this.productId) {
                try {
                    fetch(`editProductView/${this.productId}`)
                        .then(response => {
                            if (response.status === 401) {
                                window.location.hash = '#/products'
                            }
                            return response.json()
                        })
                        .then(data => {
                            if (data.products) {
                                this.product = data.products
                            }
                        })
                } catch (err) {
                    console.log(err)
                }
            }
        },
        uploadFile(event) {
            this.file = event.target.files[0];
        },
        async addProduct() {
            let form = new FormData();
            form.append('title', this.product.title);
            form.append('description', this.product.description);
            form.append('price', this.product.price);
            form.append('category', this.product.category);
            form.append('file', this.file);
            try {
                await fetch('addProduct', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: form
                }).then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            this.product = '';
                        }
                        this.error = data.error;
                    })
            } catch (err) {
                return;
            }
        },
        async update(productId) {
            let form = new FormData();
            form.append('title', this.product.title);
            form.append('description', this.product.description);
            form.append('price', this.product.price);
            form.append('category', this.product.category);
            form.append('file', this.file);
            form.append('id', productId);
            try {
                await fetch(`editProduct/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: form
                }).then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            this.product = '';
                            window.location.hash = '#/products';
                        }
                        this.error = data.error;
                    })
            } catch (err) {
            }
        }
    },
    created() {
        this.addProductComponent()
        this.editProductComponent();
    }
}
</script>