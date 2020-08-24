<template>
    <div>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" @click.prevent="$router.push('/dashboard')" class="brand-link">
            <img src="/img/icon_127.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">{{this.$store.getters.appName}}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex" v-if="this.$store.getters.loggedIn">
                    <div class="image">
                    <img v-bind:src="this.$store.getters.user.avatar" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                    <a href="#" class="d-block">{{this.$store.getters.user.fullname}}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <sidebar-menu :init="true"></sidebar-menu>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    </div>
</template>

<script>
import Menu from './Menu';
export default {
    components: {
        'sidebar-menu': Menu,
    },

    data() {
        return {
            items: [],
            user: {},
        }
    },

    created() {
        this.getUser()
    },

    methods: {
        getUser()
        {
            // console.log('users', this.$store.getters.user)
        }
    }
}
</script>