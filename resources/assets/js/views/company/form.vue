<template>
    <div class="panel container-fluid" v-if="show">
        <div class="panel-heading">
            <span class="panel-title">{{ title }} Receiving Invoice</span>
        </div>
        <div class="panel-body">
            
            
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Company Address</label>
                        <input type="text" class="form-control" v-model="form.address">
                        <small class="error-control" v-if="errors.address">
                            {{errors.address[0]}}
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
                        <label>Company Email</label>
                        <input type="text" class="form-control" v-model="form.email">
                        <small class="error-control" v-if="errors.email">
                            {{errors.email[0]}}
                        </small>
                    </div>
                </div>
              
            </div>


            <div class="row">
                
                <input type="file" class="form-control" @change="onImageChange">
            </div>
        </div>
        <div class="panel-footer flex-end">
            <div>
                <button class="btn btn-primary" :disabled="isProcessing" @click="onSave">Save</button>
                <button class="btn" :disabled="isProcessing" @click="onCancel">Cancel</button>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
import Vue from 'vue'
import {byMethod, get} from '../../lib/api'
import {Typeahead} from '../../components/typeahead'
import {objectToFormData} from "../../lib/helpers";

function initialize(to) {
    let urls = {
        // 'create': `/api/invoices/create`,
        'edit': `/api/company/${to.params.id}/edit`
    }
    return (urls[to.meta.mode] || urls['create'])
}

export default {
    components: {Typeahead},
    data() {
        return {
            form: {},
            errors: {},
            isProcessing: false,
            show: false,
            resource: '/',
            store: '/api/company/',
            method: 'POST',
            title: 'Create',
            image: '',
        }
    },
    beforeRouteEnter(to, from, next) {
        get(initialize(to))
            .then((res) => {
                next(vm => vm.setData(res))
            })
    },
    beforeRouteUpdate(to, from, next) {
        this.show = false
        get(initialize(to))
            .then((res) => {
                this.setData(res)
                next()
            })
    },
    computed: {
        subTotal() {
            return this.form.items.reduce((carry, item) => {
                return carry + (Number(item.total))
            }, 0)
        },
        total() {
            return this.subTotal - Number(this.form.pay)
        }
    },
    methods: {
        onImageChange(e) {
            this.form.invoice_img = e.target.files[0];
        },
        onTotal() {
            this.form.sub_total = this.subTotal
        },
        setData(res) {
            Vue.set(this.$data, 'form', res.data.form)
            this.customerURL = '/api/search/customer?id=' + this.$route.query.id
            if (this.$route.meta.mode === 'edit') {
                this.store = `/api/company/${this.$route.params.id}`
                this.method = 'PUT'
                this.title = 'Edit'
            }
            this.show = true
            this.$bar.finish()
        },
       
        
       
        onCancel() {
            if (this.$route.meta.mode === 'edit') {
                this.$router.push(`${this.resource}/${this.form.id}`)
            } else {
                this.$router.push(`${this.resource}`)
            }
        },
        onSave() {
            this.errors = {}
            this.isProcessing = true
            byMethod(this.method, this.store, this.form)
                .then((res) => {
                    if (res.data && res.data.saved) {
                        this.success(res)
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                    this.isProcessing = false
                })


        },
        success(res) {
            // this.$router.push(`${this.resource}`)
            this.$router.push(`/${res.data.id}`)
        }
    }
}
</script>
