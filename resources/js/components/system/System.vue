<template>
    <div>

        <div class="row">
            <!-- System vars -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">System vars</h5>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <div class="form-group" v-for="(mvar, keyv) in meta.vars" :key="keyv">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" @change="handleVar($event, mvar)" class="custom-control-input" :id="'customSwitch'+keyv" :checked="mvar.value==1">
                            <label class="custom-control-label" :for="'customSwitch'+keyv">{{mvar.label}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Cache</h5>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-wrench"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a href="#" @click.prevent="clearCache()" class="dropdown-item">
                                        <i class="fas fa-hand-sparkles fa-fw"></i> clear</a>
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-cloud-upload-alt fa-fw"></i> charge</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0" style="display: block;">

                        <div class="list-group">
                            <a href="#"  v-for="(cache, keyc) in meta.caches" :key="keyc"
                                @click.prevent="cache.check=!cache.check"
                                class="list-group-item list-group-item-action ">
                                
                                {{cache.label}} <i class="fas fa-check text-success" v-if="cache.check"></i>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="col-md-4">
                <div class="card">
              <div class="card-header">
                <h5 class="card-title">Monthly Recap Report</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <a href="#" class="dropdown-item">Action</a>
                      <a href="#" class="dropdown-item">Another action</a>
                      <a href="#" class="dropdown-item">Something else here</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">Separated link</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-center">
                      <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                      Add Products to Cart
                      <span class="float-right"><b>160</b>/200</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      Complete Purchase
                      <span class="float-right"><b>310</b>/400</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Visit Premium Page</span>
                      <span class="float-right"><b>480</b>/800</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Send Inquiries
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            </div>
        </div>

                

    </div>
</template>

<script>
    export default {

        data() {
            return {
                meta: {
                    caches: [],
                }
            }
        },

        created() {
            this.getMeta();
        },

        mounted() {
            this.modal = $('#modal-crud');
        },

        methods: {

            handleVar( evt, mvar)
            {
                let value = evt.target.checked;

                mvar.value = value===true? 1: 0;

                axios.post('/api/config', {
                    'resource': 'save-var',
                    'var': mvar,
                }).then( res => {
                    console.log(res.data);
                    toast.fire({ icon: 'success', title: 'Variable saved!' });
                }).catch( err => {
                    console.log(err);
                    evt.target.checked = !value;
                    toast.fire({ icon: 'error', title: err.request.statusText });
                })
            },

            clearCache()
            {
                let num = 0;
                this.meta.caches.forEach( x => {
                    if( x.check ) num++
                })

                if( num == 0){
                    toast.fire({ icon: 'warning', title: 'select at last one cache' });
                    return;
                }

                axios.post('/api/config', {
                    'resource': 'clear-cache',
                    'caches': this.meta.caches,
                }).then( res => {
                    toast.fire({ icon: 'success', title: 'Cache cleared!' });
                }).catch( err => {
                    toast.fire({ icon: 'error', title: err.request.statusText });
                    console.log(err);
                })
            },

            getMeta() {
                axios.get('/api/config?resource=meta')
                .then( res => {
                    this.meta.caches = res.data.cache.map( x => {
                        return {
                            'key': x.key,
                            'label': x.label,
                            'check': false,
                        };
                    })

                    this.meta.vars = res.data.vars;

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
            
        },
    }
</script>