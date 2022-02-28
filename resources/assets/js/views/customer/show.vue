<template>
    <div class="panel container-fluid card bg-primary text-white shadow" >
        <div class="panel-heading">
            <span class="panel-title">Customer Details</span>
            <div>
                <router-link to="/customers" class="btn">Back</router-link>
                <router-link :to="`/customer/${model.id}/edit`" class="btn">Edit</router-link>
                <button class="btn btn-error" @click="deleteItem">Delete</button>
            </div>
        </div>
        <div class="panel-body">
            <div class="document">
                <div class="row">
                    <div class="col-6">

                        <div>
                            <span>{{model.firstname}}</span>
                            <pre>{{model.email}}</pre>
                            <pre>{{model.phone}}</pre>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="document-body">
                    <table class="table document-table">
                        <thead>
                            <tr>
                                <th>R</th>
                                <th>SPH</th>
                                <th>CYL</th>
                                <th>AXIS</th>
                                <th>VIS</th>

                                <th>L</th>
                                <th>SPH</th>
                                <th>CYL</th>
                                <th>AXIS</th>
                                <th>VIS</th>
                            </tr>
                        </thead>
                            <tr>
                                <th>Distance</th>
                                <th>{{model.r1}}</th>
                                <th>{{model.r2}}</th>
                                <th>{{model.r3}}</th>
                                <th>{{model.r4}}</th>

                                <th>Distance</th>
                                <th>{{model.r5}}</th>
                                <th>{{model.r6}}</th>
                                <th>{{model.r7}}</th>
                                <th>{{model.r8}}</th>
                            </tr>


                            <tr>
                                <th>Near</th>
                                <th>{{model.r9}}</th>
                                <th>{{model.r10}}</th>
                                <th>{{model.r11}}</th>
                                <th>{{model.r12}}</th>

                                <th>Near</th>
                                <th>{{model.r13}}</th>
                                <th>{{model.r14}}</th>
                                <th>{{model.r15}}</th>
                                <th>{{model.r16}}</th>
                            </tr>


                            <tr>
                                <th>Contact Lens</th>
                                <th>{{model.r17}}</th>
                                <th>{{model.r18}}</th>
                                <th>{{model.r19}}</th>
                                <th>{{model.r20}}</th>

                                <th>Contact Lens</th>
                                <th>{{model.r21}}</th>
                                <th>{{model.r22}}</th>
                                <th>{{model.r23}}</th>
                                <th>{{model.r24}}</th>
                            </tr>


                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import {get, byMethod} from '../../lib/api'

    export default {
        data () {
            return {
                show: false,
                model: {

                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/customer/${to.params.id}`)
                .then((res) => {
                   next(vm => vm.setData(res))
                })
        },

        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.model)
                this.show = true
                this.$bar.finish()
            },
            deleteItem() {
                byMethod('delete',`/api/customer/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) {
                            this.$router.push('/customers')
                        }
                })
            }
        }
    }
</script>
