<template>
    <div>
        <!-- Card Table -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-8">
                        <h5> 
                            <i class="fas fa-tags fa-fw"></i>
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
                            <th>{{ $t('title') | capitalize }}</th>
                            <th>{{ $t('period') | capitalize }}</th>
                            <th>{{ $t('start') | capitalize }}</th>
                            <th>{{ $t('end') | capitalize }}</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id">
                            <td>{{item.id}}</td>
                            <td>{{item.title}}</td>
                            <td>{{item.period_text}}</td>
                            <td>{{item.date_start |datePE}}</td>
                            <td>{{item.date_end |datePE}}</td>
                            <td class="center">

                                <a class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-cog fa-fw"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="#" @click.prevent="show('show', item)" class="dropdown-item"> <i class="text-info fa fa-eye fa-fw"></i> {{ $t('show') }} </a>
                                    <a href="#" @click.prevent="show('edit', item)" class="dropdown-item"> <i class="text-success fa fa-edit fa-fw"></i> {{ $t('edit') }} </a>
                                    <a href="#" @click.prevent="show('delete', item)" class="dropdown-item"> <i class="text-danger fa fa-trash fa-fw"></i> {{ $t('delete') }} </a>

                                    <a href="#" @click.prevent="show('teachers', item)" class="dropdown-item"> <i class="text-primary fa fa-trash fa-fw"></i> {{ $t('teachers') }} </a>

                                    <router-link :to="{ name:'report.general', params: {evaluation_id:1}}" class="dropdown-item">
                                        <i class="text-purple fa fa-file fa-fw"></i> {{ $t('report') }}
                                    </router-link>
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
                resource: 'evaluations',
                items: {},
                search:  '',
            }
        },

        mounted() {
            this.getItems();
        },

        methods: {

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
                if( action == 'create')
                    this.$router.push('/evaluations/create');
                else if( action == 'show' )
                    this.$router.push(`/evaluations/${item.id}`);
                else
                    this.$router.push(`/evaluations/${item.id}/${action}`);
            },
 
        },
    }
</script>