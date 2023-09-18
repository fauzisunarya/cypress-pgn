<template>
    <div class="icl-color-picker" ref="colorpicker">
        <div class="input-group">
        <div @click="showPicker" class="input-group-prepend">
            <div class="input-group-text" :style="{'backgroundColor':vModel}"></div>
        </div>
        <input
            class="form-control"
            type="text"
            v-model="vModel"
            @click="showPicker"
            >
        </div>
        <chrome-picker v-if="active" :value="vModel" @input="updateColor" />
    </div>
</template>

<script>
import { Chrome } from 'vue-color'

export default {
  name: 'ColorPicker',
  components: {
    'chrome-picker': Chrome
  },
  props: {
    vModel: String
  },
  data(){
    return {
      active: false
    }
  },
  watch: {
    vModel: {
      handler(after, before){
        if( after !== before )
        //   this.$store.commit('dirty', true)
        this.$emit('changeColor', this.vModel);
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
            this.vModel = color.hex
          else
            this.vModel = '#' + color.hex;
        })
      } else {
        this.$nextTick().then(()=>{
          this.vModel = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`

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