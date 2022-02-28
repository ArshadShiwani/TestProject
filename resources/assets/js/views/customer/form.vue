<template>
    <div class="panel container-fluid" >
        <div class="panel-heading">
            <span class="panel-title">Customer Registration</span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" v-model="form.client">
                        <small class="error-control" v-if="errors.client">
                            {{errors.client[0]}}
                        </small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" v-model="form.phone">
                        <small class="error-control" v-if="errors.phone">
                            {{errors.phone[0]}}
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" v-model="form.email">
                        <small class="error-control" v-if="errors.email">
                            {{errors.email[0]}}
                        </small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <button class="btn btn-primary" :disabled="isProcessing" @click="onSave">Save</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get,byMethod } from '../../lib/api'
    import {Typeahead } from '../../components/typeahead'
    import VueSimpleAlert from "vue-simple-alert";
    function initialize(to) {
        let urls = {
            'create': `/api/customer/create?`,
            'edit': `/api/customer/${to.params.id}/edit`
        }

        return (urls[to.meta.mode] || urls['create'])
    }
    Vue.use(VueSimpleAlert);
    export default {
        components: { Typeahead},
        data () {
            return {
                form: {},
                errors: {},
                isProcessing: false,
                show: false,
                resource: '/customers',
                store: '/api/customer',
                method: 'POST',
                title: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            get(initialize(to))
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        methods:{
            onSave() {
                this.errors = {}
                this.isProcessing = true
                byMethod(this.method, this.store, this.form)
                    .then((res) => {
                        if(res.data && res.data.saved) {

                            this.success(res)
                        }
                    })
                    .catch((error) => {
                        if(error.response.status === 422) {
                            this.errors = error.response.data.errors
                        }
                        else if(error.response.status === 500) {
                            this.$alert(
                                "Can not Add customer May be Reason is duplicate Record.",
                                "",
                                "warning"
                            );
                        }
                        this.isProcessing = false
                    })
            },
            success(res) {
                if(this.$route.query.q){
                    this.$router.push(`${this.resource}?q=`+this.$route.query.q)
                }
                else{
                    this.$router.go(-1)
                }

            },
            setData(res) {

                Vue.set(this.$data, 'form', res.data.form)
                this.form.type=this.$route.query.q
                this.title=res.data.title

                if(this.$route.meta.mode === 'edit') {
                    this.store = `/api/customer/${this.$route.params.id}`
                    this.method = 'PUT'
                    this.title = 'Edit'
                    this.newInvoice=true
                }

                this.show = true
                this.$bar.finish()
            },
        },





    }
</script>
