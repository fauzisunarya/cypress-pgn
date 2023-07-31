<template>
<div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
  <label class="form-label">{{data.label}}</label>
  <div class="input-group" style="width:170px">
    <font-picker
      :api-key="'AIzaSyBWRHp4IDXAaudSFwz2Vvdz045Zq8XrSwM'"
      :active-font="data.value"
      :options="options"
      @change="fontChanged"/>
  </div>
</div>
</template>

<script>
import FontPicker from 'font-picker-vue';

export default {
  name: 'field-typography',
  components: {
    'font-picker': FontPicker
  },
  props: {
    data: Object
  },
  computed: {
    options(){
      return {
        name: this.data.label.replace(' ','-').toLowerCase()
      }
    }
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
  methods: {
    fontChanged(value){
      this.data.value = value.family
      console.log(value);
      this.$store.commit('dirty', true)
    }
  }
}
</script>

<style lang="less">
  div[id^="font-picker"] ul{
    background-color: #fbfbfb;
  }
  .font-picker .font-picker-list li button, .font-picker .font-picker-list li button{
    transition: background-color .3s ease;
  }
  .font-picker .font-picker-list li button:hover, .font-picker .font-picker-list li button:focus{
    background-color: var(--primary);
    color: white;
  }
</style>