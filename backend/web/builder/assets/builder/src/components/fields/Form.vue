<template>
  <div>
    <div class="icl-ed-field mb-3" :class="{'icl-ed-field--horizontal':data.horizontal}">
      <label class="form-label">{{data.label}}</label>
      <div class="field-select">
        <select class="form-control" v-model="data.value">
          <option value="">Formulir Kontak (Sistem)</option>
          <option v-for="option in $store.state.project.forms" :value="option.form_id">{{option.form_name}}</option>
        </select>
      </div>
    </div>

    <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
      <label class="form-label">Tampilkan Label</label>
      <div class="field-switch">
        <label>
          <input type="checkbox" v-model="data.settings.label">
          <span class="field-switch-ui"></span>
        </label>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'field-form',
  props: {
    data: Object
  },
  mounted(){
    // console.log(this.$store.state.project)
  },
  watch: {
    'data.value' : {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    },
    'data.settings.label' : {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    },
  },
}
</script>

<style>select{font-size: inherit}</style>