<template>
    <div v-if="show" class="panel">
        <div class="panel-heading">
            <span class="panel-title">{{ model.number }}</span>
        </div>
        <div id="printMe" class="panel-body">
            <img src="logo.png" width="800" height="100">
            <div class="document">
                <div class="row">
                    <div class="col-6">
                        <strong>To:</strong>
                        <div>
                            <span>{{ model.customer.client }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-offset-12">
                        <table class="document-summary">
                            <tbody>
                            <tr>
                                <td colspan="2">
                                    <span class="document-title">INVOICE</span>
                                </td>

                            </tr>
                            <tr>
                                <td>Number</td>
                                <td>{{ model.number }}</td>
                            </tr>

                            <tr>
                                <td>NTN/STRN</td>
                                <td>{{ model.customer.ntn_strn }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ model.customer.phone }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>{{ model.date }}</td>
                            </tr>
                            <tr v-if="model.phone">
                                <td>phone</td>
                                <td>{{ model.phone }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="document-body">
                    <table class="table document-table">
                        <thead>
                        <tr>
                            <th class="w-10">Description</th>
                            <th class="w-12">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in model.items" :key="item.id">
                            <td class="w-3">{{ item.description }}</td>
                            <td class="w-12">
                                {{ item.total }}
                            </td>

                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4">Sub Total</td>
                            <td>{{ model.sub_total | formatMoney }}</td>
                        </tr>
                        <tr>
                            <td colspan="4">Paid</td>
                            <td>{{ model.amounts[0].amount_pay | formatMoney }}</td>
                        </tr>
                        <tr>
                            <td colspan="4">Balance</td>
                            <td>{{ balance |  formatMoney }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="document-footer">
                    <strong>Terms and Conditions</strong>
                    {{ model.terms_and_conditions }}
                </div>
                <p><b>Bank Account Details Are Given Below</b><br>FARHAN & CO ADVOCATES, <b>UBL BANK</b>, FTC BRANCH
                    <br><b>AC # 1796230477838</b><br><br><b>Thanking You</b><br><br><br><b>For FARHAN & CO ADVOCATES</b>
                    <br>Counsel for the assesses</p>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">

import Vue from 'vue'
import {byMethod, get} from '../../lib/api'
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
            balance:0
        }
    },
    beforeRouteEnter(to, from, next) {
        get(`/api/invoices/${to.params.id}`)
            .then((res) => {
                next(vm => vm.setData(res))
            })

    },
    beforeRouteUpdate(to, from, next) {


    },
    methods: {
        setData(res) {
            Vue.set(this.$data, 'model', res.data.model)
            this.balance=res.data.model.amounts[0].amount_left-res.data.model.amounts[0].amount_pay
            if(this.balance<0){
                this.balance=0
            }
            this.show = true
            this.$bar.finish()
            setTimeout(() => {   window.print(); }, 2000);

        },
        printPage(){
            window.print()
        }
    }
}
</script>
