<template>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

            <div class="alert alert-success" role="alert" v-if="message">
                {{message}}
            </div>

            <form @submit.prevent="sendEmail()">
                <div class="input-group mb-3">
                    <input type="email" 
                        class="form-control" 
                        placeholder="Email" 
                        autofocus
                        v-model="form.email"
                        :class="{ 'is-invalid': form.errors.has('email') }">

                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span> </div>
                    </div>
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-1">
                <router-link :to="{name: 'login'}">Login</router-link>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return  {
            form: new Form({
                email: '',
            }),
            message: '',
        }
    },

    methods: {
        sendEmail(){
            this.message = '';
            this.form.post('/api/password/email')
            .then( res => {
                this.message = res.data.message;
            })
            .catch( err => {
                console.error( err.response )
            })
        }
    }
}
</script>

