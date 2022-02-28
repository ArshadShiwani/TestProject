<template>

    <div class="panel container-fluid">

        <div class="panel-heading">
            <span class="panel-title h3 mb-0 text-gray-800">{{ title }}</span>  
            <div>
                <router-link :to=invoiceType class="btn btn-primary">
                    New Invoice
                </router-link>
                <router-link :to=formtype class="btn btn-success" style="color: white">
                    Register Customer
                </router-link>
            </div>

        </div>

        <div class="col-6">
            <div class="form-group">
                <label>
                    Search Client
                </label>
                <input v-model="form.customer" class="form-control" type="text" @input="onSearch">
            </div>


        </div>


        <div class="panel-body">
            <span class="title">
            </span>
            <table class="table table-link">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>phone</th>
                    <th>All Invoice</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in model.data" :key="item.data">
                    <td class="w-3">{{ index + 1 }}</td>
                    <td class="w-3">{{ item.client }}</td>
                    <td class="w-3">{{ item.phone }}</td>
                    <td @click="allInvoice(item.id)"  class="w-3">All Invoice</td>
                    <td class="w-3" @click="deleteItem(item.id)">
                        delete
                    </td>
                    <td class="w-3">
                        <router-link :to="`/customers/${item.id}/edit`">Edit</router-link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer flex-between">
            <div>
                <small>Showing {{ model.from }} - {{ model.to }} of {{ model.total }}</small>
            </div>
            <div>
                <button :disabled="!model.prev_page_url" class="btn btn-sm" @click="prevPage">
                    Prev
                </button>
                <button :disabled="!model.next_page_url" class="btn btn-sm" @click="nextPage">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
import Vue from 'vue'
import {byMethod, get} from '../../lib/api'
import {Typeahead} from '../../components/typeahead'
import VueSimpleAlert from "vue-simple-alert";

Vue.use(VueSimpleAlert);

export default {
    components: {Typeahead},
    data() {
        return {
            model: {
                data: []
            },
            outStandingPerPerson: [],
            form: {},
            total: [],
            title: '',
            formtype: "",
            subTotal: 0,
            invoiceType: '',
        }
    },
    beforeRouteEnter(to, from, next) {
        get('/api/customer', to.query)
            .then((res) => {
                next(vm => vm.setData(res))
            })
    },
    beforeRouteUpdate(to, from, next) {
        get('/api/customer', to.query)
            .then((res) => {
                this.setData(res)
                next()
            })
    },
    methods: {
        onSearch() {
            console.log("dsa")
            get(`/api/customer?search=`   + this.form.customer).then((res) => {
                this.total = 0
                this.setData(res);
            });
        },
        
        allInvoice(id) {
            this.$router.push({path: `/invoices/all/${id}`})
        },
        editItem(id) {
            this.$router.push(`/customer/${item.id}`)
        },
        deleteItem(id) {

            byMethod('delete', `/api/customer/${id}`)
                .then((res) => {
                    if (res.data.deleted) {
                        this.reload()
                    }
                })
        },
        reload() {
            get('/api/customer', this.$route.query)
                .then((res) => {
                    this.setData(res)
                })
        },

      
        setData(res) {
            
            Vue.set(this.$data, 'model', res.data.results)
            
            this.formtype = '/customers/create'
            this.invoiceType = '/invoices/create'
            this.page = this.model.current_page
            this.$bar.finish()


          


        },
        nextPage() {
            if (this.model.next_page_url) {
                const query = Object.assign({}, this.$route.query)
                query.page = query.page ? (Number(query.page) + 1) : 2
                this.$router.push({
                    path: '/customers',
                    query: query
                })
            }
        },
        prevPage() {
            if (this.model.prev_page_url) {
                const query = Object.assign({}, this.$route.query)
                query.page = query.page ? (Number(query.page) - 1) : 1
                this.$router.push({
                    path: '/customers?q=' + this.$route.query.q,
                    query: query
                })
            }
        }
    }
}
</script>
