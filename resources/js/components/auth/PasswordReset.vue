<template>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

        <form @submit.prevent="reset()">
            <div class="input-group mb-3">
                    <input type="email" 
                        class="form-control" 
                        placeholder="Email" 
                        v-model="form.email"
                        :class="{ 'is-invalid': form.errors.has('email') }">

                    <div class="input-group-append">
                        <div class="input-group-text"> <span class="fas fa-envelope"></span> </div>
                    </div>
                    <has-error :form="form" field="email"></has-error>
                </div>

            <div class="input-group mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    placeholder="Password"
                    autofocus
                    v-model="form.password" 
                    :class="{ 'is-invalid': form.errors.has('password') }">
                <div class="input-group-append">
                    <div class="input-group-text"> <span class="fas fa-lock"></span> </div>
                </div>
                <has-error :form="form" field="password"></has-error>
            </div>

            <div class="input-group mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    placeholder="Confirm Password"
                    v-model="form.password_confirmation" 
                    :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                <div class="input-group-append">
                    <div class="input-group-text"> <span class="fas fa-lock"></span> </div>
                </div>
                <has-error :form="form" field="password_confirmation"></has-error>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Change password</button>
                </div>
            </div>
        </form>

        <p class="mt-3 mb-1">
            <router-link :to="{name: 'login'}">Login</router-link>
        </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</template>

<script>
export default {
    data() {
        return  {
            form: new Form({
                email: '',
                password: '',
                password_confirmation: '',
                token: '',
            }),
            message: '',
        }
    },

    created() {
        this.form.token = this.$route.params.token;
        this.form.email = this.$route.query.email;
    },

    methods: {
        reset(){
            this.form.post('/api/password/reset')
            .then( res => {
                this.message = res.data.message;
                window.location.replace("/dashboard");
                // console.log( res.data );
            })
            .catch( err => {
                console.error( err.response )
            })
        }
    }
}
</script>

