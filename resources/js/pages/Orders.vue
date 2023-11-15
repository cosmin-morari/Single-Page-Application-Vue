<template>
    <a href="#/products">{{ translate.productsPage }}</a>

    <table border="1" v-if="orders">
        <thead>
            <tr>
                <td>{{ translate.id }}</td>
                <td>{{ translate.date }}</td>
                <td>{{ translate.name }}</td>
                <td>{{ translate.contactDetails }}</td>
                <td>{{ translate.comments }}</td>
                <td>{{ translate.actionViewOrder }}</td>
            </tr>
        </thead>
        <tbody v-for="item in orders" :key="item.id">
            <tr>
                <td>{{ item.id }} </td>
                <td>{{ item.date }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.contactDetails }}</td>
                <td>{{ item.comments }}</td>
                <td>
                    <button @click="seeOrder(item.id)">{{ translate.seeOrder }}</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data(){
        return{
            translate: '',
            orders:''
        }
    },
    methods:{
        seeOrder(order){
            window.location.hash = '#/order'
            window.order = order;
        }
    },
    created(){
        fetch('/api/translation')
                .then(response => response.json())
                .then(data => {
                    this.translate = data;
                })
         fetch('orders')
                .then(response => response.json())
                .then(data => {
                    if(data.error){
                        window.location.hash = '#/login';
                    }
                    this.orders = data;
                })
    }       
}
</script>
