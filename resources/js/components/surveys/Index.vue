<template>
    <div>
        <!-- Modal Crud -->
        <div class="modal fade" id="modal-crud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-tags fa-fw"></i>
                            {{ $t(resource) | capitalize }}
                        </h5>
                        
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()" class="row">

                            <div class="col-lg-6">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">{{ $t('title') | capitalize }}</label>
                                    <input type="text" id="title" class="form-control form-control-sm"
                                        v-model="form.title" 
                                        :class="{ 'is-invalid': form.errors.has('title') }" 
                                        :readonly="isReadOnly">
                                    <has-error :form="form" field="title"></has-error>

                                    <input type="hidden" class="form-control form-control-sm"
                                        v-model="form.id" 
                                        :class="{ 'is-invalid': form.errors.has('id') }" 
                                        :readonly="isReadOnly">
                                    <has-error :form="form" field="id"></has-error>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="descrip">{{ $t('description') | capitalize }}</label>
                                    <textarea rows="3" id="descrip" class="form-control form-control-sm"
                                        v-model="form.descrip" 
                                        :class="{ 'is-invalid': form.errors.has('descrip') }" 
                                        :readonly="isReadOnly"></textarea>
                                    <has-error :form="form" field="descrip"></has-error>
                                </div>

                                <!-- Scale -->
                                <div class="form-group">
                                    <label for="scale_id">{{ $t('scale') | capitalize }}</label>
                                    <select id="scale_id" class="form-control form-control-sm"
                                        v-model="form.scale_id"
                                        :class="{ 'is-invalid': form.errors.has('scale_id') }" 
                                        :disabled="isReadOnly">
                                            <option v-for="(scale, key) in scales" :key="key" :value="scale.id">{{scale.name}}</option>
                                    </select>
                                    <has-error :form="form" field="scale_id"></has-error>
                                </div>

                                <!-- Level -->
                                <div class="form-group">
                                    <label for="level_id">{{ $t('level') | capitalize }}</label>
                                    <select type="text" id="level_id" class="form-control form-control-sm"
                                        v-model="form.level_id" 
                                        :class="{ 'is-invalid': form.errors.has('level_id') }" 
                                        :disabled="isReadOnly">
                                            <option v-for="(level, key) in levels" :key="key" :value="level.id">{{level.name}}</option>
                                    </select>
                                    <has-error :form="form" field="level_id"></has-error>
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <!-- Items -->
                                <div class="form-group">
                                    <label for="items">{{ $t('items') | capitalize }}
                                        <a href="#" class="badge badge-secondary"
                                            @click.prevent="addItem()"
                                            v-if="!isReadOnly"> 
                                            <i class="fas fa-plus"></i></a>
                                    </label>

                                    <div style="height: 305px; overflow-y: scroll;" class="is-invalid">
                                        <table class="table table-sm table-bordered" :class="{ 'is-invalid': form.errors.has('items') }" >
                                            <tbody>
                                                <tr v-for="(item, keyi) in form.items" :key="keyi">
                                                    <td width="30" class="text-center">{{keyi}}</td>
                                                    <td width="30" class="text-center">{{item.id}}</td>
                                                    <td>
                                                        <!-- style="border:0;" -->
                                                        <input type="text" class="form-control form-control-sm"
                                                            style="border:0;"
                                                            v-model="form.items[keyi].name" 
                                                            :class="{ 'is-invalid': form.errors.has(`items.${keyi}.name`) }"
                                                            :readonly="isReadOnly">

                                                        <has-error :form="form" :field="`items.${keyi}.name`"></has-error>
                                                    </td>
                                                    <td width="50">
                                                        <!-- style="border:0;" -->
                                                        <input type="text" class="form-control form-control-sm"
                                                            style="border:0;"
                                                            v-model="form.items[keyi].value" 
                                                            :class="{ 'is-invalid': form.errors.has(`items.${keyi}.value`) }"
                                                            :readonly="isReadOnly">

                                                        <has-error :form="form" :field="`items.${keyi}.value`"></has-error>
                                                    </td>

                                                    <td width="30" class="text-center" v-if="!isReadOnly">
                                                        <a href="#" @click.prevent="removeItem(keyi)"> 
                                                            <i class="fas fa-trash text-red"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <has-error :form="form" field="items"></has-error>
                                    </div>
                                    <div v-if="errorConstraint" class="help-block invalid-feedback" v-html="errorConstraint"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group text-right mb-0">
                                    <a href="#" @click.prevent="closeModal()" class="btn btn-sm btn-secondary">{{ $t('close') }}</a>
                                    <button v-if="form.action=='create'" class="btn btn-sm btn-primary">{{ $t('create') }}</button>
                                    <button v-if="form.action=='edit'" class="btn btn-sm btn-success">{{ $t('edit') }}</button>
                                    <button v-if="form.action=='delete'" class="btn btn-sm btn-danger">{{ $t('delete') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="modal-footer"></div> -->
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
                            {{ $t(resource) | capitalize }}
                            <a href="#" @click.prevent="openModal('create')" class="badge badge-primary badge-pill">{{$t('add')}}</a>
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
                            <th>{{ $t('title') | capitalize }}</th>
                            <th>{{ $t('description') | capitalize }}</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td>{{item.id}}</td>
                            <td>{{item.title}}</td>
                            <td>{{item.descrip}}</td>
                            <td class="center">
                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="openModal('show', item)" class="dropdown-item"> <i class="text-info fa fa-eye fa-fw"></i> {{ $t('show') }} </a>
                                    <a href="#" @click.prevent="openModal('edit', item)" class="dropdown-item"> <i class="text-success fa fa-edit fa-fw"></i> {{ $t('edit') }} </a>
                                    <a href="#" @click.prevent="openModal('delete', item)" class="dropdown-item"> <i class="text-danger fa fa-trash fa-fw"></i> {{ $t('delete') }} </a>
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
                resource: 'surveys',
                items: {},
                search:  '',
                modal: null,
                form: new Form({
                    id: '',
                    title: '',
                    descrip: '',
                    items: [],
                    level_id: '',
                    scale_id: '',
                    action: 'create',
                    _method: 'POST',
                }),
                levels: [],
                scales: [],
                errorConstraint: '',
            }
        },

        created() {
            this.getItems();
            this.getMetaCrudSurveys();
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

            getMetaCrudSurveys(){
                axios.get('/api/common?resource=meta-crud-survey')
                .then( res => {
                    this.levels = res.data.levels;
                    this.scales = res.data.scales;
                })
                .catch( err => {
                    console.log('ERROR', err);
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
                this.errorConstraint = '';
                this.form.reset();
                this.form.clear();

                if(action !== 'create'){
                    axios.get(`/api/${this.resource}/${item.id}`)
                    .then( res => {
                        this.form.id = res.data.id;
                        this.form.title = res.data.title;
                        this.form.descrip = res.data.descrip;
                        this.form.items = res.data.items;
                        this.form.scale_id = res.data.scale_id;
                        this.form.level_id = res.data.level_id;
                    })
                    .catch( err => {
                        console.log('ERROR', err);
                    })
                }

                this.form.action = action;

                this.form._method = action=='create'? 'POST': 
                                    (action=='edit'? 'PUT': 
                                    (action=='delete'? 'DELETE': 'Unknown'));
                this.modal.modal('show');
            },

            closeModal(){
                this.modal.modal('hide');
            },

            addItem()
            {
                this.form.items.unshift({ id: '', name: '', value: '3' });
            },

            removeItem(index)
            {
                this.form.items.splice(index, 1);
                this.form.clear();
            },

            crud() {
                this.$Progress.start();

                this.errorConstraint = '';

                let url = this.form.action=='create'? `/api/${this.resource}`: `/api/${this.resource}/${this.form.id}`;

                this.form.post(url)
                .then( res => {
                    this.$Progress.finish();
                    this.form.reset();
                    this.getItems();
                    this.closeModal();
                })
                .catch( err => { 
                    this.$Progress.fail();
                    if( err.response.data.indexOf('SQLSTATE[23000]') >= 0 ){
                        this.errorConstraint = "Integrity constraint violation";
                        this.errorConstraint = err.response.data;
                    }
                    console.error('ERROR', err.response);
                })
            },
 
        },
    }
</script>