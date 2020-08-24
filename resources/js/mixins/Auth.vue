<script>
export default {
    methods: {
        
        $auth() {
            return User;
        },

        $can(permissionName) {
            return Permissions.indexOf(permissionName) !== -1;
        },

        $canSome() {
            let can = false;
            for( var i = 0; i< arguments.length; i++)
            {
                if( this.$can(arguments[i]) ){
                    can = true;
                    break;
                }
            }
            return can;
        },

        $canOnly() {

        },


    }
}
</script>