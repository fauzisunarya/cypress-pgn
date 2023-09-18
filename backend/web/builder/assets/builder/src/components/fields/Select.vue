<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="field-select">

      <div v-if="!data.options_data">
        <select class="form-control" v-model="data.value">
          <option v-for="(value, key) in data.options" :value="key" :key="key">{{value}}</option>
          <option
            v-if="data.catalog_category && $store.state.project.catalog_category"
            v-for="option in $store.state.project.catalog_category"
            :key="option.id"
            :value="option.slug">
              {{option.title}}
          </option>
          <option
            v-if="data.post_category && $store.state.project.post_category"
            v-for="option in $store.state.project.post_category"
            :key="option.id"
            :value="option.slug">
              {{option.title}}
          </option>
        </select>  
      </div>

      <div v-else>
        <select class="form-control" v-model="data.value">
          <option value="">Sistem (default)</option>
          <option v-if="data.options_data && data.options_data == 'form'" v-for="option in $store.state.project.forms" :value="option.form_id">{{option.form_name}}</option>
        </select>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'field-select',
  props: {
    data: Object
  },
  watch: {
    'data.value' : {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
}
</script>

<style>select{font-size: inherit}</style>