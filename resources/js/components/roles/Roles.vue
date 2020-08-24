<template>
    <div>
        <!-- Modal Crud -->
        <div class="modal fade" id="modal-crud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-tags fa-fw"></i>
                            {{this.resource | capitalize}}
                        </h5>
                        
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" v-model="form.name" id="name" class="form-control form-group-sm"
                                    :class="{ 'is-invalid': form.errors.has('name') }" :readonly="action=='show'||action=='delete'">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <select id="permissions" v-model="form.permissions" multiple class="form-control form-control-sm" size="8" :class="{ 'is-invalid': form.errors.has('permissions') }" :readonly="action=='show'||action=='delete'">
                                    <option v-for="(permission, key) in permissions" :key="key" :value="permission.id" v-html="permission.name"></option>
                                </select>
                                <has-error :form="form" field="permissions"></has-error>
                            </div>

                            <div class="form-group text-right mb-0">
                                <a href="#" @click.prevent="close()" class="btn btn-sm btn-secondary">Close</a>
                                <button v-if="action=='create'" class="btn btn-sm btn-primary">Create</button>
                                <button v-if="action=='edit'" class="btn btn-sm btn-success">Edit</button>
                                <button v-if="action=='delete'" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                            
                        </form>
                    </div>
                    <!-- <div class="modal-footer">
                        
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Card Table -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-8">
                        <h5> 
                            <i class="fas fa-tags fa-fw"></i>
                            {{resource |capitalize}} 
                            <a href="#" @click.prevent="show('create', {})" class="badge badge-primary badge-pill">{{$t('add')}}</a>
                        </h5>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <form @submit.prevent="getItems()">
                            <div class="input-group input-group-sm">
                                <input type="search" v-model="search" class="form-control form-control-sm" placeholder="serach...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">{{$t('search')}}</button>
                                </div>
                            </div>
                        </form>
                       
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Created</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td>{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.created_at |dateEs}}</td>
                            <td class="center">

                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="show('show', item)" class="dropdown-item"> <i class="text-info fa fa-eye fa-fw"></i>Show</a>
                                    <a href="#" @click.prevent="show('edit', item)" class="dropdown-item"> <i class="text-success fa fa-edit fa-fw"></i>Edit</a>
                                    <a href="#" @click.prevent="show('delete', item)" class="dropdown-item"> <i class="text-danger fa fa-trash fa-fw"></i>Delete</a>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">

                <pagination :data="items" size="small" align="right" :limit="5"
                    @pagination-change-page="getItems" class="mb-0"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                modal: null,
                resource: 'roles',
                action: 'create', //create, edit, delete
                items: {},
                search:  '',
                form: new Form({
                    id: '',
                    name: '',
                    permissions: [],
                }),
                permissions: [],
            }
        },

        created() {
            this.getItems();
            this.getPermissions();
        },

        mounted() {
            this.modal = $('#modal-crud');
        },

        methods: {

            getPermissions() {
                axios.get('/api/common?resource=all-permissions')
                .then( res => {
                    this.permissions = res.data;
                })
                .catch( err => {
                    console.log('ERROR_PERMISSIONS', err);
                })
            },

            getItems( page=1) {
                axios.get(`/api/${this.resource}?page=${page}&search=${this.search}`)
                .then( res => {
                    this.items = res.data;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            show(action, item={} ) {
                this.action = action;
                this.form.reset();

                if(this.action !== 'create'){
                    this.form.fill(item);
                    this.form.permissions = Object.keys(this.form.permissions);
                }
                this.modal.modal('show');
            },

            close(){
                this.modal.modal('hide');
            },

            crud() {
                this.$Progress.start();
                let http;
                if( this.action == 'create')
                    http = this.form.post(`/api/${this.resource}`);
                if( this.action == 'edit')
                    http = this.form.put(`/api/${this.resource}/${this.form.id}`);
                if( this.action == 'delete')
                    http = this.form.delete(`/api/${this.resource}/${this.form.id}`);
             
                http
                .then( res => {
                    this.$Progress.finish();
                    this.form.reset();
                    this.action = 'create';
                    this.getItems();
                    this.close();
                    
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log('EROR', res)
                })
            },
            
        },
    }
</script>