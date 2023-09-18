<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="icl-color-picker" ref="colorpicker">
      <div class="input-group">
        <div @click="showPicker" class="input-group-prepend">
          <div class="input-group-text" :style="{'backgroundColor':data.value}"></div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="data.value"
          @click="showPicker"
          >
      </div>
      <chrome-picker v-if="active" :value="data.value" @input="updateColor" />
    </div>
  </div>
</template>

<script>
import { Chrome } from 'vue-color'

export default {
  name: 'field-color',
  components: {
    'chrome-picker': Chrome
  },
  props: {
    data: Object
  },
  data(){
    return {
      active: false
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
    showPicker(){
      document.addEventListener('click', this.documentClick);
      this.active = true;
    },
    hidePicker(){
      document.removeEventListener('click', this.documentClick);
      this.active = false;
    },
    updateColor(color){
      if ( color.rgba.a == 1 ) {
        this.$nextTick().then(()=>{
          if ( color.hex.charAt(0) == '#' )
            this.data.value = color.hex
          else
            this.data.value = '#' + color.hex;
        })
      } else {
        this.$nextTick().then(()=>{
          this.data.value = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`

        })
      }
    },
    documentClick(e){
      var el = this.$refs.colorpicker,
          target = e.target;
      if ( el ) {
        if ( el !== target && !el.contains(target) ) {
          this.hidePicker();
        }
      }
    },
  }
}
</script>

<style lang="less">
.icl-color-picker{
  position: relative;
  .vc-chrome{
    position: absolute;
    z-index: 99;
    top: 100%;
    right: 0;
    margin-top: 15px;
    margin-bottom: 30px;
    box-shadow: 0 1px 2px rgba(0,0,0,.2), 0 10px 30px rgba(0,0,0,.3)
  }
  .input-group{
    flex-wrap: nowrap;
  }
  .input-group-text{
    width: 40px;
  }
  .form-control{
    width: 122px;
    font-weight: 500;
    font-size: 14px;
  }
}

  
</style>