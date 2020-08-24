<style >
    .select-gate {
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }
</style>
<template>
    <div>
        <!-- Modal Crud -->
        <div class="modal fade" id="modal-crud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-tags fa-fw"></i>
                            {{ $t(this.resource) | capitalize }}
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

                                <input type="hidden" class="form-control form-control-sm"
                                    v-model="form.id" 
                                    :class="{ 'is-invalid': form.errors.has('id') }" 
                                    :readonly="isReadOnly">
                                <has-error :form="form" field="id"></has-error>

                            </div>

                            <!-- Intervals -->
                            <div class="form-group">
                                <label for="config"> {{ $t('intervals') | capitalize }}
                                    <a href="#" @click.prevent="addInterval()" class="badge badge-secondary" v-if="!isReadOnly"><i class="fas fa-plus"></i></a>
                                </label>

                                <table class="table table-sm table-hover table-bordered" :class="{ 'is-invalid': form.errors.has('intervals') }">
                                    <thead>
                                        <tr style="text-align:center">
                                            <td  width="200">{{ $t('range') | capitalize }}</td>
                                            <td colspan="2">{{ $t('value') | capitalize }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(interval, keyi) in form.intervals" :key="keyi">
                                            <td>
                                                <div class="input-group" style="width:100%" 
                                                    :class="{ 
                                                        'is-invalid': form.errors.has(`intervals.${keyi}.v1`) || form.errors.has(`intervals.${keyi}.v2`),
                                                    }">

                                                    <select class="form-control form-control-sm select-gate" v-model="interval.g1" :disabled="isReadOnly">
                                                            <option v-for="(gate, key) in gates.lower" :key="key" :value="key">{{gate}}</option>
                                                    </select>

                                                    <input type="text" class="form-control form-control-sm"
                                                        style="text-align:center" 
                                                        v-model="interval.v1"
                                                        :class="{ 'is-invalid': form.errors.has(`intervals.${keyi}.v1`) }"
                                                        :readonly="isReadOnly">

                                                    <input type="text" class="form-control form-control-sm" style="text-align:center" value=":" disabled >

                                                    <input type="text" class="form-control form-control-sm"
                                                        style="text-align:center" 
                                                        v-model="interval.v2"
                                                        :class="{ 'is-invalid': form.errors.has(`intervals.${keyi}.v2`) }"
                                                        :readonly="isReadOnly">
                                                    

                                                    <select class="form-control form-control-sm select-gate" v-model="interval.g2" :disabled="isReadOnly">
                                                        <option v-for="(gate, key) in gates.upper" :key="key" :value="key">{{gate}}</option>
                                                    </select>
                                                </div>
                                                <has-error :form="form" :field="`intervals.${keyi}.v1`"></has-error>
                                                <has-error :form="form" :field="`intervals.${keyi}.v2`"></has-error>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm" 
                                                        v-model="interval.value"
                                                        :class="{ 'is-invalid': form.errors.has(`intervals.${keyi}.value`) }"
                                                        :readonly="isReadOnly">
                                                    <has-error :form="form" :field="`intervals.${keyi}.value`"></has-error>
                                                    <span class="input-group-append">
                                                        <div class="form__field">
                                                            <div class="form__input">
                                                                 <v-swatches v-model="interval.colour"
                                                                    show-fallback 
                                                                    fallback-input-type="color"
                                                                    popover-x="right">
                                                                        <button type="button"
                                                                            slot="trigger" 
                                                                            class="btn btn-sm btn-default"
                                                                            style="height: 29px;"
                                                                            :style="{'background-color': interval.colour}"
                                                                            :value="interval.colour">
                                                                                <i class="fas fa-tint"></i>
                                                                            </button>
                                                                </v-swatches>
                                                            </div>
                                                        </div>
                                                        <!-- <button type="button" class="btn btn-info">Go!</button> -->
                                                    </span>
                                                </div>
                                                
                                            </td>

                                            <td width="30" class="text-center" v-if="!isReadOnly">
                                                <a href="#" @click.prevent="removeInterval(keyi)"> 
                                                    <i class="fas fa-trash text-red"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <has-error :form="form" field="intervals"></has-error>
                            </div>
                            
                            <!-- Acctions -->
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
                            <th width="50">#</th>
                            <th>{{ $t('name') | capitalize }}</th>
                            <th>{{ $t('config') | capitalize }}</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td class="text-center">{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.intervals.map(i => i.value).join(', ')}}</td>
                            <td class="center">

                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="openModal('show', item)" class="dropdown-item"> <i class="text-info fa fa-eye fa-fw"></i>{{$t('show')}}</a>
                                    <a href="#" @click.prevent="openModal('edit', item)" class="dropdown-item"> <i class="text-success fa fa-edit fa-fw"></i>{{$t('edit')}}</a>
                                    <a href="#" @click.prevent="openModal('delete', item)" class="dropdown-item"> <i class="text-danger fa fa-trash fa-fw"></i>{{$t('delete')}}</a>
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

    // import verte from 'verte';
    // import 'verte/dist/verte.css';
    // import 'bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css';
    // import "bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js";

    import VSwatches from 'vue-swatches'
    import 'vue-swatches/dist/vue-swatches.css'

    export default {
        // components: { verte },
        components: { VSwatches },

        data() {
            return {
                modal: null,
                resource: 'levels',
                items: {},
                search:  '',
                form: new Form({
                    id: '',
                    name: '',
                    intervals: [],
                    action: 'create',
                    _method: 'POST',
                }),

                gates: {
                    lower: [ '[', '<'],
                    upper: [ ']', '>'],
                },

                color: '#1CA085',
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
            },
        },

        methods: {
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },

            addInterval() {
                this.form.intervals.unshift({ 
                    g1: "0", 
                    g2: "0", 
                    v1: 0, 
                    v2: 0, 
                    value: "",
                    colour: this.getRandomColor(), 
                });
            },

            removeInterval( index ) {
                this.form.intervals.splice(index, 1);
                this.form.clear();
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
                let url = this.form.action=='create'? `/api/${this.resource}`
                    : `/api/${this.resource}/${this.form.id}`;

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