<template>
    <div>

        
        <div class="list-group">
            <div class="list-group-item p-0" v-for="(item, keyi) in items" :key="keyi">
                <div class="card collapsed-card mb-0" style="border:none; box-shadow:none">
                <div class="card-header pb-0" style="background-color:white;">

                    <div class="row">
                        <div class="col-sm-8">
                            <h3 class="card-title">
                                <i :class="item.icon"></i>
                                {{ item.label }}


                                <button v-if="item.items.length" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </h3>
                        </div>
                        <div class="col-sm-4 text-right" >
                            <a href="#" @click.prevent="openModal('show', item)" title="show"><i class="fas fa-eye fa-fw text-secondary"></i></a>
                            <a href="#" @click.prevent="openModal('edit', item)" title="edit"><i class="fas fa-edit fa-fw text-secondary"></i></a>
                            <a href="#" @click.prevent="openModal('delete', item)" title="delete"><i class="fas fa-trash fa-fw text-secondary"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 pl-3" style="display: block;">
                    <menu-list @clicked="openModal" :items="item.items" v-if="item.items.length"></menu-list>
                </div>
            </div>
            </div>
            
        </div>
        

    </div>
    
</template>

<script>
    export default {

        props: [ 'items' ],

        name: 'menu-list',

        data() {
            return {
                list: {},
            }
        },

        methods: {
            openModal(action, item) 
            {
                this.$emit('clicked', action, item)
            },

            openModalChild(action, item)
            {
                this.openModal(action, item)
            }
        }
    }
</script>