<template>
    <div class="row justify-content-center" style="margin-top:100px;">
        <div class="col-md-6">
            <div class="error-template text-center">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    Sorry, an error has occured, Requested page not found!
                </div>
                <div class="error-actions">
                    <router-link class="btn btn-primary" to="/dashboard">
                        <i class="fas fa-home"></i>
                        Home
                    </router-link>
                    <router-link class="btn btn-default" to="/contact-support">
                        <i class="fas fa-envelope"></i>
                        Contact Support
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>
