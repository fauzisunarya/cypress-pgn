<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="field-select">

      <div>
        <select class="form-control" v-model="data.value">
          <option value="" >Custom button link from package</option>
          <option v-for="(page, key) in pages" :value="page.id" :key="key">To {{page.title}}</option>
          <!-- <option v-for="(value, key) in data.options" :value="key" :key="key">{{value}}</option> -->
        </select>  
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'field-package-link',
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
  computed:{
    pages(){
      return this.$store.state.project.menus.filter(menu=>menu.type==='page')
    }
  }
}
</script>