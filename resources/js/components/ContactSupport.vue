<template>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-envelope"></i>
                    Contact Us
                </div>
                <div class="card-body">
                    <form @submit="send()" class="row">
                        <div class="col-12 form-group">
                            <label for="name">Name (*)</label>
                            <input type="text" id="name" v-model="form.name" class="form-control">
                        </div>
                        <div class="col-12 form-group">
                            <label for="email">Email (*)</label>
                            <input type="email" id="email" v-model="form.email" class="form-control">
                        </div>
                        <div class="col-12 form-group">
                            <label for="subject">Subject (*)</label>
                            <textarea type="text" id="subject" v-model="form.subject" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12 form-group mb-0 text-right">
                            <button class="btn btn-primary"> 
                                <i class="fas fa-paper-plane fa-fw"></i>Send </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    name: 'Romel Diaz',
                    email: 'root.romel@gmail.com',
                    subject: 'I do not found the page example.com',
                }),
            }
        }
    }
</script>
