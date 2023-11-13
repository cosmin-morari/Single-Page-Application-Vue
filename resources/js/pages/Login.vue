<template>
    <div class="container">
        <h3>{{translate.login}}</h3>
        <input type="text" name="adminMail" class="adminMail" :placeholder="translate.userName" v-model="form.adminMail">
        <br><br>
        <input type="password" name="adminPassword" class="adminPassword" :placeholder="translate.password" v-model="form.adminPassword">
        <br><br>
        <div style="color:red" class="error adminMail">{{invalidCredentials}}</div>
        <button @click="login" type="submit">{{translate.login}}</button>
    </div>
</template>

<script>

export default {
    data (){
        return {
            translate: '',
            form : {
                adminMail : '',
                adminPassword : ''
            }, 
            invalidCredentials: '',
        }
    },
    methods: {
        
        async login(){
            try{
                const response = await fetch('login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                }).then(response => response.json())
                        .then(data => {
                                this.invalidCredentials = data.message
                            if(data.admin){
                                window.location.hash ='/products'
                            }
                })
            }catch (err) {
                return;
            }
        }
    }
    ,
    created(){
        fetch('/api/translation')
            .then(response => response.json())
            .then(data => {
                this.translate = data
            })
    }
}

</script>