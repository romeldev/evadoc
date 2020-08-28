<template>
    <div>
        <!-- Modal Crud -->
        <div class="modal fade" id="modal-crud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-tags fa-fw"></i>
                            {{ $t(this.resource) | capitalize}}
                        </h5>
                        
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()">

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">{{ $t('order') | capitalize }}</label>
                                <input type="text" id="order" class="form-control form-control-sm"
                                    v-model="form.order" 
                                    :class="{ 'is-invalid': form.errors.has('order') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="order"></has-error>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">{{ $t('label') | capitalize }}</label>
                                <input type="text" id="label" class="form-control form-control-sm"
                                    v-model="form.label" 
                                    :class="{ 'is-invalid': form.errors.has('label') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="label"></has-error>
                            </div>

                            <!-- path -->
                            <div class="form-group">
                                <label for="name">{{ $t('path') | capitalize }}</label>
                                <input type="text" id="path" class="form-control form-control-sm"
                                    v-model="form.path" 
                                    :class="{ 'is-invalid': form.errors.has('path') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="path"></has-error>
                            </div>

                            <!-- icon -->
                            <div class="form-group">
                                <label for="name">{{ $t('icon') | capitalize }}</label>
                                <input type="text" id="icon" class="form-control form-control-sm"
                                    v-model="form.icon" 
                                    :class="{ 'is-invalid': form.errors.has('icon') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="icon"></has-error>
                            </div>

                            <!-- parent -->
                            <div class="form-group">
                                <label for="name">{{ $t('parent') | capitalize }}</label>
                                <select id="menu_id" class="form-control form-control-sm"
                                    v-model="form.menu_id" 
                                    :class="{ 'is-invalid': form.errors.has('menu_id') }" 
                                    :readonly="isReadOnly">
                                    <option value=''>----------</option>
                                    <option v-for="(menu, key) in items" :key="key" :value="menu.id">{{menu.label}}</option>
                                </select>

                                <has-error :form="form" field="menu_id"></has-error>
                            </div>

                           

                            <!-- actions -->
                            <div class="form-group text-right mb-0">
                                <a href="#" @click.prevent="closeModal()" class="btn btn-sm btn-secondary">{{$t('close')}}</a>
                                <button v-if="form.action=='create'" class="btn btn-sm btn-primary">{{$t('create')}}</button>
                                <button v-if="form.action=='edit'" class="btn btn-sm btn-success">{{$t('edit')}}</button>
                                <button v-if="form.action=='delete'" class="btn btn-sm btn-danger">{{$t('delete')}}</button>
                            </div>
                            
                        </form>
                    </div>
                    <!-- <div class="modal-footer">
                        
                    </div> -->
                </div>
            </div>
        </div>



        <a href="#" @click.prevent="openModal('create', {})" 
            class="btn btn-sm btn-primary mb-2" title="new"><i class="fas fa-plus fa-fw"></i>New</a>

        <menu-list @clicked="openModal" :items="items"></menu-list>
        

    </div>
</template>

<script>
    import MenuList from './MenuList';

    export default {

        components: { 'menu-list': MenuList },

        data() {
            return {
                modal: null,
                resource: 'menus',
                items: {},
                search:  '',
                form: new Form({
                    id: '',
                    order: '',
                    label: '',
                    path: '',
                    icon: '',
                    menu_id: '',
                    action: 'create',
                    _method: 'POST',
                }),
            }
        },

        created() {
            this.getItems();
        },

        mounted() {
            this.modal = $('#modal-crud');
        },

        computed: {
            isReadOnly() {
                return this.form.action=='show'||this.form.action=='delete';
            }
        },

        methods: {

            async getItems() {
                axios.get(`/api/${this.resource}`)
                .then( res => {
                    this.items = res.data.data;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            openModal(action, item ) {
                this.form.reset();
                this.form.clear();

                this.form.icon = 'fas fa-circle';

                if(action !== 'create'){
                    this.form.fill( JSON.parse(JSON.stringify(item)) );
                }

                this.form.action = action;

                this.form._method = action=='create'? 'POST': 
                                    (action=='edit'? 'PUT': 
                                    (action=='delete'? 'DELETE': 'Unknown'));
                this.modal.modal('show');
            },

            closeModal(){
                this.form.reset();
                this.form.clear();
                this.modal.modal('hide');
            },

            crud() {
                this.$Progress.start();

                let url = this.form.action=='create'? `/api/${this.resource}`: `/api/${this.resource}/${this.form.id}`;

                this.form.post(url)
                .then( res => {
                    this.$Progress.finish();
                    this.getItems();
                    this.closeModal();
                    this.$store.dispatch('retrieveMenus')
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log('EROR', res)
                })
            },
            
        },
    }
</script>