<template>
    <nav class="mt-2" v-if="init==true">
        <!-- <ul data-widget="treeview" role="menu" data-accordion="false" class="nav nav-pills nav-sidebar flex-column nav-legacy nav-child-indent"> -->
        <ul data-widget="treeview" role="menu" data-accordion="false" class="nav nav-pills nav-sidebar flex-column">

            <li  v-for="(item, key) in this.$store.getters.menus" :key="key" 
                class="nav-item"
                :class="{'has-treeview': item.items.length}">

                <router-link :to="item.path" class="nav-link" :active-class="'active'" v-if="item.items.length==0">
                    <i class="nav-icon" :class="item.icon"></i>
                    <p>
                        {{item.label}}
                    </p>
                </router-link>

                <a href="#" class="nav-link" v-else>
                    <i class="nav-icon" :class="item.icon"></i>
                    <p>
                        {{item.label}}
                        <i class="right fas fa-angle-left" v-if="item.items.length"></i>

                    </p>
                </a>
                <tree-menu :init="false" :items="item.items"></tree-menu>
            </li>

        </ul>
    </nav>

    <ul class="nav nav-treeview" v-else>
        <li  v-for="(item, key) in list" :key="key" 
            class="nav-item"
            :class="{'has-treeview': item.items.length}">

            <router-link :to="item.items.length?'#': item.path" class="nav-link" :active-class="'active'">
                <i class="nav-icon" :class="item.icon"></i>
                <p>
                    {{item.label}}
                    <i class="right fas fa-angle-left" v-if="item.items.length"></i>
                </p>
            </router-link>
        </li>
    </ul>
</template>

<script>

export default {

    props: [ 'init', 'items' ],

    name: 'tree-menu',
    
    data() {
        return {
            list: [],
        }
    },

    created() {
        if( this.init ) {
            // this.list = this.$store.getters.menus
        }else{
            this.list = this.items;
        }
    },

    computed: {
        
    },

    methods: {

    }
}
</script>