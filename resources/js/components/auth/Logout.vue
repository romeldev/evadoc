<template>
    <div>
        Logout...
    </div>
</template>

<script>

export default {
    mounted() {
        this.$store.dispatch("destroyToken")
        .then( response => {
            this.$router.push({ name: 'login'} );
        }).catch( err => {})
    },
}
</script>