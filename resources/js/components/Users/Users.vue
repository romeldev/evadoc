<template>
    <div>
        <!-- Modal Crud -->
        <div class="modal fade" id="modal-crud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-users fa-fw"></i>
                            {{ $t(resource) | capitalize }}
                        </h5>
                        
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()" class="row">

                            <div class="col-md-6">
                                <input type="hidden" v-model="form.id">
                                <div class="form-group">
                                    <label for="fullname">{{ $t('fullname') | capitalize }}</label>
                                    <input type="text" v-model="form.fullname" name="fullname" id="fullname" class="form-control form-group-sm"
                                        :class="{ 'is-invalid': form.errors.has('fullname') }" :readonly="action=='show'||action=='delete'">
                                    <has-error :form="form" field="fullname"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="name">{{ $t('name') | capitalize }}</label>
                                    <input type="text" v-model="form.name" id="name" class="form-control form-group-sm"
                                        :class="{ 'is-invalid': form.errors.has('name') }" :readonly="action=='show'||action=='delete'">
                                    <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="email">{{ $t('email') | capitalize }}</label>
                                    <input type="email" v-model="form.email" id="email" class="form-control form-group-sm"
                                        :class="{ 'is-invalid': form.errors.has('email') }" :readonly="action=='show'||action=='delete'">
                                    <has-error :form="form" field="email"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="password">{{ $t('password') | capitalize }}</label>
                                    <input type="password" v-model="form.password" id="password" class="form-control form-group-sm"
                                        :class="{ 'is-invalid': form.errors.has('password') }" :readonly="action=='show'||action=='delete'">
                                    <has-error :form="form" field="password"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="school_code">{{ $t('school') | capitalize }}</label>
                                    <select id="school_code" class="form-control form-group-sm"
                                        v-model="form.school_code"
                                        :class="{ 'is-invalid': form.errors.has('school_code') }" 
                                        :readonly="action=='show'||action=='delete'">
                                        <option v-for="(school, keyc) in schools" :key="keyc" :value="school.code">{{school.name}}</option>
                                    </select>
                                    <has-error :form="form" field="school_code"></has-error>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roles">{{ $t('roles') | capitalize }}</label>
                                    <select id="roles" v-model="form.roles" multiple class="form-control form-control-sm" size="8" :class="{ 'is-invalid': form.errors.has('roles') }" :readonly="action=='show'||action=='delete'">
                                        <option v-for="(role, key) in roles" :key="key" :value="role.id" :title="role.name">{{role.name}}</option>
                                    </select>
                                    <has-error :form="form" field="roles"></has-error>
                                    <!-- <p>user-roles: {{form.roles}} </p> -->
                                </div>


                                <div class="form-group custom-file">
                                    <input type="file" id="photo"  class="custom-file-input"  @change="handleFilePhoto" :class="{ 'is-invalid': form.errors.has('photo') }" :readonly="action=='show'||action=='delete'">
                                    <label class="custom-file-label" for="photo" :data-browse="$t('photo')">{{ $t('select')+'...' | capitalize }}</label>
                                    <has-error :form="form" field="photo"></has-error>
                                </div>
                            </div>
                            

                            <div class="col-12 form-group text-right mt-3 mb-0">
                                <a href="#" @click.prevent="close()" class="btn btn-sm btn-secondary">{{ $t('close') }}</a>
                                <button v-if="action=='create'" class="btn btn-sm btn-primary">{{ $t('create') }}</button>
                                <button v-if="action=='edit'" class="btn btn-sm btn-success">{{ $t('edit') }}</button>
                                <button v-if="action=='delete'" class="btn btn-sm btn-danger">{{ $t('delete') }}</button>
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
                            <i class="fas fa-users fa-fw"></i>
                            {{ $t(resource) | capitalize }}
                            <a href="#" @click.prevent="show('create', {})" class="badge badge-primary badge-pill">{{$t('add')}}</a>
                        </h5>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <form @submit.prevent="getItems()">
                            <div class="input-group input-group-sm">
                                <input type="search" v-model="search" class="form-control form-control-sm" :placeholder="$t('search')+'...'">
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
                            <th>{{ $t('fullname') | capitalize }}</th>
                            <th>{{ $t('name') | capitalize }}</th>
                            <th>{{ $t('email') | capitalize }}</th>
                            <th>{{ $t('created') | capitalize }}</th>
                            <th width="50" v-if="$canSome('users.show', 'users.edit', 'users.delete')"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td>{{item.id}}</td>
                            <td>{{item.fullname}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.email}}</td>
                            <td>{{item.created_at |dateEs}}</td>
                            <td class="center">
                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="show('show', item)" class="dropdown-item" > <i class="text-info fa fa-eye fa-fw"></i> {{ $t('show') }} </a>
                                    <a href="#" @click.prevent="show('edit', item)" class="dropdown-item" > <i class="text-success fa fa-edit fa-fw"></i> {{ $t('edit') }} </a>
                                    <a href="#" @click.prevent="show('delete', item)" class="dropdown-item">  <i class="text-danger fa fa-trash fa-fw"></i> {{ $t('delete') }} </a>
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
                resource: 'users',
                action: 'create', //create, edit, delete
                items: {},
                search:  '',
                form: new Form({
                    id: '',
                    fullname: 'romel diaz ramos',
                    name: 'rodira',
                    email: 'romel@gmail.com',
                    password: 'password',
                    photo: '',
                    roles: [],
                    school_code: '',
                    school_name: '',
                    _method: 'PUT',
                }),
                formRoles: [ 1, 3],
                roles: [],
                schools: [],
            }
        },

        created() {
            this.getItems();
            this.getRolesAndSchools();
        },

        mounted() {
            this.modal = $('#modal-crud');
        },

        methods: {

            getSchoolName(school_code){
                let school = this.schools.find(s => s.code==school_code);
                return school.name;
            },

            handleFilePhoto(e) {
                this.form.photo = e.target.files[0];
            },

            getRolesAndSchools() {
                axios.get('/api/common?resource=all-roles-and-schools')
                .then( res => {
                    this.roles = res.data.roles;
                    this.schools = res.data.schools;
                })
                .catch( err => {
                    console.log('ERROR_ROLES', err);
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
                this.formRoles =[];
                this.form.reset();

                if(this.action !== 'create'){
                    this.form.fill(item);
                    this.form.roles = Object.keys(this.form.roles);
                }

                this.modal.modal('show');
            },

            close(){
                this.modal.modal('hide');
            },

            crud() {
                this.$Progress.start();
                
                this.form._method = this.getHttpMethod();
                this.form.school_name = this.getSchoolName(this.form.school_code);

                let url = `/api/${this.resource}`;
                if( this.action!='create') url = `${url}/${this.form.id}`;

                this.form.submit('post', url, {
                    headers: { 'X-Content-Type-Options': 'nosniff', },
                    // Transform form data to FormData
                    transformRequest: [function (data, headers) {
                        console.log('DATA_OBJECT',data )
                        return objectToFormData(data);
                    }],

                    onUploadProgress: e => {
                        // Do whatever you want with the progress event
                        // console.log(e)
                    }
                })
                .then( res => {
                    console.log(res.data);
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

            getHttpMethod()
            {
                if( this.action == 'create')
                    return 'POST';
                else if( this.action == 'edit' )
                    return 'PUT';
                else if( this.action == 'delete' )
                    return 'DELETE';
            }
        },
    }
</script>