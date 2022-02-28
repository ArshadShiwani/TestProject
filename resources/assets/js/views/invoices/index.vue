<template>
  <div class="container-fluid" >
    <div class="panel-heading">
      <span class="panel-title">Invoices</span>
      <div>
        <router-link to="/invoices/create" class="btn btn-primary">
          New Invoice
        </router-link>
<!--         <h3>{{total}}</h3>-->
      </div>
    </div>
     <div class="col-6">
          <div class="form">
              <input type="text" class="form-control" @input="onSearch"  v-model="form.customer">
          </div>
      </div>
    <div class="panel-body">
      <table class="table table-link">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>CINI NO</th>
            <th>NTN/STRN</th>
            <th>Contact NO</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in model.data"
            :key="item.data"

          >
            <td class="w-1">{{ item.id }}</td>
            <td class="w-3">{{ item.customer.client }}</td>
            <td class="w-3">{{ item.customer.nici }}</td>
            <td class="w-3">{{ item.customer.ntn_strn }}</td>
            <td class="w-3">{{ item.customer.phone }}</td>
            <td class="w-3" @click="detail(item.id)">Detail</td>
            <td class="w-3">{{ item.last_invoice_amount.amount_left }}</td>
              <td class="w-3" @click="detail(item.id)">ledger</td>
              <td><a href=""></a></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="panel-footer flex-between">
      <div>
        <small
          >Showing {{ model.from }} - {{ model.to }} of {{ model.total }}</small
        >
      </div>
      <div>
        <button
          class="btn btn-sm"
          :disabled="!model.prev_page_url"
          @click="prevPage"
        >
          Prev
        </button>
        <button
          class="btn btn-sm"
          :disabled="!model.next_page_url"
          @click="nextPage"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import Vue from "vue";
import { get } from "../../lib/api";
import { Typeahead } from "../../components/typeahead";
export default {
  components: { Typeahead },
  data() {
    return {
      model: {
        data: [],
      },
      model1: {
        data1: [],
      },

      form: {
      },
      total: 0,
      customerURL: "/api/customers",
    };
  },
  beforeRouteEnter(to, from, next) {
    get("/api/invoices", to.query).then((res) => {
      next((vm) => vm.setData(res));
    });
  },
  beforeRouteUpdate(to, from, next) {
    get("/api/invoices", to.query).then((res) => {
      this.setData(res);
      next();
    });
  },
  methods: {
    onSearch() {
      get(`/api/search/invoices/${this.form.customer}`).then((res) => {
          this.total=0
          this.setData(res);
      });
    },
    detail($id)
    {
      this.$router.push(`/invoice/detail/${$id}`)
    },
    detailsPage(item) {
      this.$router.push(`/invoices/${item.id}`);
    },
    setData(res) {
      Vue.set(this.$data, "model", res.data.results);
      if(res.data.out_standing.length>0) {
          for (var i = 0; i < res.data.out_standing.length; i++) {
              this.total += res.data.out_standing[i].last_invoice_amount.amount_left
          }
          this.page = this.model.current_page;
          this.$bar.finish();
      }
      else{
          this.total=0
      }
    },
    nextPage() {
      if (this.model.next_page_url) {
        const query = Object.assign({}, this.$route.query);
        query.page = query.page ? Number(query.page) + 1 : 2;

        this.$router.push({
          path: "/invoices",
          query: query,
        });
      }
    },
    prevPage() {
      if (this.model.prev_page_url) {
        const query = Object.assign({}, this.$route.query);
        query.page = query.page ? Number(query.page) - 1 : 1;

        this.$router.push({
          path: "/invoices",
          query: query,
        });
      }
    },
  },
};
</script>
