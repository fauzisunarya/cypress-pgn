<template>
<div class="icl-ed-field" >
  <label class="form-label">{{data.label}}</label>
  <v-select
    class="select"
    :options="options"
    :filterable="false"
    v-model="data.value"
    label="product_name"
    @search="onSearch">
    <template slot="no-options">
      Ketik nama produk atau kode SKU..
    </template>
  </v-select>

</div>
</template>
<script>
import vSelect from 'vue-select'
import debounce from 'lodash/debounce'
import Axios from 'axios'
import 'vue-select/dist/vue-select.css'

export default {
  name: 'field-product',
  components: { 'v-select': vSelect },
  props: {
    data: Object
  },
  data(){
    return {
      options: [],
      value: null
    }
  },
  methods: {
    onChange(value){
      this.value = value;
    },

    onSearch(search, loading) {
      const api = this.$root.config.api;

      if ( search ){
        loading(true);
        this.search(loading, search, this, api);
      }
    },

    search: debounce((loading, search, vm, api) => {
      Axios.get( api+'/get_product',
        { params: { search: escape(search) } },
      ).then(response => {
        vm.options = response.data.content
        loading(false);
      });
    }, 350)
  }
}
</script>

<style>
.select{
  background-color: white;
}
</style>