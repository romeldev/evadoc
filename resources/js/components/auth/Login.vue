<template>
  <div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <div class="alert alert-info" role="alert">
            Error: {{ error }}
        </div>

        <div class="alert alert-danger" role="alert" v-if="form.errors.has('email')">
            {{form.errors.errors.email[0]}}
        </div>

        <form @submit.prevent="login()">
            <div class="input-group mb-3">
                    <input type="email" 
                        class="form-control" 
                        placeholder="Email" 
                        autofocus
                        v-model="form.username"
                        :class="{ 'is-invalid': form.errors.has('name') }">

                    <div class="input-group-append">
                        <div class="input-group-text"> <span class="fas fa-envelope"></span> </div>
                    </div>
                    <has-error :form="form" field="name"></has-error>
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

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                    <input type="checkbox" id="remember" v-model="form.remember">
                    <label for="remember">
                        Remember Me
                    </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
            <!-- /.col -->
            </div>
        </form>

        <p class="mb-1">
            <router-link :to="{name: 'password.request' }"> I forgot my password </router-link>
        </p>
    </div>
  </div>
</template>

<script>

export default {

    data() {
        return {
            form: new Form({
                username: 'rohadira.rhdr@gmail.com',
                password: 'password',
                remember: false,
            }),

            error: '',
        }
    },

    methods: {
        login() {
            this.$store
            .dispatch("retrieveToken", {
                username: this.form.username,
                password: this.form.password,
                remember: this.form.remember
            })
            .then( res => {
                this.$router.push({ name: "dashboard" });
            })
            .catch( err => {
                this.error = err.response.data;
            });
        }
    }
}
</script>