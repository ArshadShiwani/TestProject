<template>
    <div class="panel" v-if="show">
        <div class="panel-heading">
            <span class="panel-title">{{model.number}}</span>
            <div>
                <router-link to="/invoices" class="btn">Back</router-link>
                <router-link :to="`/invoices/${model.id}/edit`" class="btn">Edit</router-link>
                <button class="btn" @click="print">print</button>
                <button class="btn btn-error" @click="deleteItem">Delete</button>

            </div>
        </div>
        <div id="printMe" class="panel-body">
            <div class="document">
                <div class="row">
                    <div class="col-6">
                        <strong>Customer Name:</strong>
                        <div>
                            <span><strong>{{model.customer_name}}</strong></span>
                        </div>
                    </div>
                    <div class="col-6 col-offset-12">
                        <table class="document-summary">
                            <tbody>
                                <tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="document-title">INVOICE</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Job No.</td>
                                    <td>{{model.number}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>{{model.date}}</td>
                                </tr>
                                <tr>
                                    <td>Delivery Date</td>
                                    <td>{{model.due_date}}</td>
                                </tr>
                                <tr v-if="model.phone">
                                    <td>Contact Number</td>
                                    <td>{{model.phone}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="document-body">
                    <table class="table document-table">
                        <thead>
                            <tr>
                            
                                <th>Description</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in model.items" :key="item.id">
                               
                                <td class="w-12">
                                    {{item.product}}
                                </td>
                                <td class="w-3">{{item.unit_price | formatMoney}}</td>
                                <td class="w-2">{{item.qty}}</td>
                                <td class="w-4">{{item.qty * item.unit_price | formatMoney}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Sub Total</td>
                                <td>{{model.sub_total | formatMoney}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Advance</td>
                                <td>{{model.discount | formatMoney}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Balance</td>
                                <td>{{model.total | formatMoney}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="document-footer">
                    <strong>Terms and Conditions</strong>
                    {{model.terms_and_conditions}}
                </div>
    

            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import {get, byMethod} from '../../lib/api'
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
        data () {
            return {
                show: false,
                model: {
                    items: [],
                    customer: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/invoices/${to.params.id}`)
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
            setData(res) {
                Vue.set(this.$data, 'model', res.data.model)
                this.show = true
                this.$bar.finish()
            },
            deleteItem() {
                byMethod('delete', `/api/invoices/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) {
                            this.$router.push('/invoices')
                        }
                    })
            },
            print() {
                // Pass the element id here
                this.$htmlToPaper('printMe');
            }
        },

    }
</script>
