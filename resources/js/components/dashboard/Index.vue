<style>
    table > thead {
        text-align: center;
        font-weight: bold;
    }
</style>

<template>
    <div>
        <div  v-if="!this.$store.getters.loggedIn" class="login-page" style="min-height: 512.391px;">
            <div class="login-box">
                <div class="login-logo">
                    <a href="/dashboard"><b>EVADOC</b></a>
                </div>
                <!-- /.login-logo -->
                <router-view></router-view>
                <vue-progress-bar></vue-progress-bar>
            </div>
            <!-- /.login-box -->
        </div>


        <div class="wrapper" v-else>

            <component-navbar></component-navbar>

            <component-main-sidebar></component-main-sidebar>

            <div class="content-wrapper pt-3">
                <!-- Content Header (Page header) -->
                <!-- <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Starter Page</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Starter Page</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <router-view></router-view>
                        <vue-progress-bar></vue-progress-bar>
                    </div>
                </div>
            </div>

            <component-control-sidebar></component-control-sidebar>

            <component-footer></component-footer>

        </div>
        <!-- ./wrapper -->
    </div>
</template>

<script>
import Nabvar from './Nabvar';
import MainSidebar from './MainSidebar';
import ControlSidebar from './ControlSidebar';
import Footer from './Footer';


export default {
    components: {
        'component-navbar': Nabvar,
        'component-main-sidebar': MainSidebar,
        'component-control-sidebar': ControlSidebar,
        'component-footer': Footer,
    },

    mounted() {
        
    }
}
</script>