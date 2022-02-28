<template>
    <div v-if="show" class="panel container-fluid card shadow">
        <div class="panel-heading">
            <span class="panel-title">{{ model.client }}</span>
            <div>
                <router-link :to=url class="btn btn-primary">
                    New Invoice
                </router-link>
                <button  class="btn btn btn-success" @click="print">Print</button>
                <button class="btn btn-secondary" @click="onBack">Back</button>
            </div>
        </div>
        <div id="printMe" class="panel-body"  >
            
            <span class="panel-title align-content-center"><b>All Invoices</b></span>
            <div >
                <div class="row">
                    <div class="col-4">
                        <div>
                            <span>Name</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div>
                            <strong>{{ model.client }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div>
                            <span>Phone</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div>
                            <strong>{{ model.phone }}</strong>
                        </div>
                    </div>
                </div>

                
               

                <div class="row" v-if="showSearch">
                    <div class="col-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input v-model="searchParms.startDate" class="form-control" type="date">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input v-model="searchParms.EndDate" class="form-control" type="date">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button class="btn btn-primary" @click="onSearch">Search</button>
                        </div>

                    </div>
                </div>


                <div class="panel-body">
                    <table class="table table-link">
                        <thead>
                        <tr>
                            <th class="w-2">Date</th>
                            <th class="w-2">Invoice Number</th>
                            <th class="w-2">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in model.invoices" :key="item.invoices" @click="detailsPage(item)">
                            <td class="w-3">{{ item.date }}</td>
                            <td class="w-3">
                                {{ item.number }}
                            </td>
                            <td class="w-2">
                                {{ item.sub_total }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>


    </div>
</template>


<script type="text/javascript">

import Vue from 'vue'
import {get} from '../../lib/api'
import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'css/app.css',
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        // 'https://unpkg.com/kidlat-css/css/kidlat.css'
    ]
}
Vue.use(VueHtmlToPaper, options);

export default {

    data() {
        return {
            show: false,
            model: {
                items: [],
                customer: {}
            },
            searchParms: {
                startDate: null,
                EndDate: null
            },
            customerURL: {
                id: null
            },
            url: null,
            showSearch:true

        }
    },
    beforeRouteEnter(to, from, next) {
        get(`/api/invoices/all/${to.params.id}`)
            .then((res) => {
                next(vm => vm.setData(res))
            })
    },
    beforeRouteUpdate(to, from, next) {
        this.show = false
        get(`/api/invoices/${to.params.id}`)
            .then((res) => {
                this.setData(res)
                next()
            })
    },
    methods: {
        onSearch() {
            get(`/api/invoices/all/${this.model.id}`, this.searchParms)
                .then((res) => {
                    this.setData(res)
                })
        },
        setData(res) {


            Vue.set(this.$data, 'model', res.data.model)
            this.show = true
            this.$bar.finish()
            this.customerURL.id = this.$route.params.id
            this.url = '/invoices/create/?id=' + this.$route.params.id

        },
        print() {
            // Pass the element id here
            this.showSearch=false
            setTimeout(() => {    this.$htmlToPaper('printMe'); }, 100);
            setTimeout(() => {        this.showSearch=true }, 100);
            // this.$htmlToPaper('printMe');

        },
        detailsPage(item) {
            this.$router.push(`/invoices/${item.id}`)
        },
        onBack(){
            this.$router.go(-1)
        }

    }
}
</script>
