import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'hash',
    routes: [
        {path: '/', redirect: '/dashboard'},
        {path: '/dashboard', component: require('../views/dashboard/index.vue').default},

        {path: '/invoices', component: require('../views/invoices/index.vue').default},
        {path: '/invoices/create', component: require('../views/invoices/form.vue').default},
        {path: '/invoices/:id/edit', component: require('../views/invoices/form.vue').default, meta: {mode: 'edit'}},
        {path: '/invoices/:id', component: require('../views/invoices/show.vue').default},
        {path: '/invoices/all/:id', component: require('../views/invoices/invoices.vue').default},


        {path: '/customers/', component: require('../views/customer/index.vue').default},
        {path: '/customers/create', component: require('../views/customer/form.vue').default},
        {path: '/customers/:id', component: require('../views/customer/show.vue').default},
        {path: '/customers/:id/edit', component: require('../views/customer/form.vue').default, meta: {mode: 'edit'}},


        {path: '/company/:id/edit', component: require('../views/company/form.vue').default, meta: {mode: 'edit'}},

       

        {path: '/print/invoice/:id', component: require('../views/print/invoice.vue').default},


    ]

})

export default router
