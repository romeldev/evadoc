

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
                                <label for="name">{{ $t('name') | capitalize }}</label>
                                <input type="text" id="name" class="form-control form-control-sm"
                                    v-model="form.name" 
                                    :class="{ 'is-invalid': form.errors.has('name') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <!-- options -->
                            <div class="form-group">
                                <label for="options">{{ $t('options') | capitalize }}
                                    <a href="#" @click.prevent="addOption()" class="badge badge-secondary" v-if="!isReadOnly"><i class="fas fa-plus"></i></a>
                                </label>
                                <table class="table table-sm table-bordered" :class="{ 'is-invalid': form.errors.has('options') }">
                                    <thead>
                                        <tr style="text-align:center">
                                            <td width="60">{{ $t('value') | capitalize }}</td>
                                            <td colspan="2">{{ $t('text') | capitalize }}</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(option, keyo) in form.options" :key="keyo">

                                            <td>
                                                <input type="text" class="form-control form-control-sm" style="text-align:center"
                                                    v-model="option.value"
                                                    :class="{ 'is-invalid': form.errors.has(`options.${keyo}.value`) }"
                                                    :readonly="isReadOnly">
                                                <has-error :form="form" :field="`options.${keyo}.value`"></has-error>
                                            </td>
                                            
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="option.text"
                                                    :class="{ 'is-invalid': form.errors.has(`options.${keyo}.text`) }"
                                                    :readonly="isReadOnly">
                                                <has-error :form="form" :field="`options.${keyo}.text`"></has-error>
                                            </td>

                                            <td width="30" class="text-center" v-if="!isReadOnly">
                                                <a href="#" @click.prevent="removeOption(keyo)"> 
                                                    <i class="fas fa-trash text-red"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                                <has-error :form="form" field="options"></has-error>
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

        <!-- Card Table -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-8">
                        <h5> 
                            <i class="fas fa-tags fa-fw"></i>
                            {{ $t(this.resource) | capitalize }}
                            <a href="#" @click.prevent="openModal('create')" class="badge badge-primary badge-pill">{{$t('add')}}</a>
                        </h5>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <form @submit.prevent="getItems()">
                            <div class="input-group input-group-sm">
                                <input type="search" v-model="search" class="form-control" :placeholder="$t('search')+'...'">
                                <div class="input-group-append">
                                    <button class="btn btn-xs btn-outline-secondary" type="submit" id="button-addon2">{{$t('search')}}</button>
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
                            <td>N</td>
                            <td>{{ $t('name') | capitalize }}</td>
                            <td>{{ $t('options') | capitalize }}</td>
                            <td width="80"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td>{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.options.map(i => i.text).join(', ')}}</td>
                            <td>

                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="openModal('show', item)" class="dropdown-item"> <i class="text-info fa fa-eye fa-fw"></i> {{$t('show')}}</a>
                                    <a href="#" @click.prevent="openModal('edit', item)" class="dropdown-item"> <i class="text-success fa fa-edit fa-fw"></i> {{$t('edit')}}</a>
                                    <a href="#" @click.prevent="openModal('delete', item)" class="dropdown-item"> <i class="text-danger fa fa-trash fa-fw"></i> {{$t('delete')}}</a>
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
                resource: 'scales',
                items: {},
                search:  '',
                form: new Form({
                    id: '',
                    name: '',
                    options: [],
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

            addOption() {
                // this.form.options.unshift({ value: "", text: "" });
                this.form.options.push({ value: "", text: "" });
            },

            removeOption( index ) {
                this.form.options.splice(index, 1);

                // this.clearErrorsOptions();
                // this.form.clear();
            },

            clearErrorsOptions() {
                let errorKeys = [];
                Object.keys(this.form.errors.errors).forEach( key => {
                    if( key.search('options') !== -1){
                        errorKeys.push(key);
                    }
                });
                // TODO: delete key from errors
                key
                errorKeys.forEach(key => {
                    console.log(key);
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

            openModal(action, item ) {
                this.form.reset();
                this.form.clear();

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
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log('EROR', res)
                })
            },
            
        },
    }
</script>