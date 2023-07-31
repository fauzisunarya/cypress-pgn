<template>
  <div class="block-divider-field">
    <div class="icl-ed-field icl-ed-field--horizontal">
      <label class="form-label">{{data.label}}</label>

      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary" :class="{'active': 'top' == this.active}">
          <input type="radio" value="top" v-model="active"> Atas
        </label>
        <label class="btn btn-primary" :class="{'active': 'bottom' == this.active}">
          <input type="radio" value="bottom" v-model="active"> Bawah
        </label>
      </div>
    </div>

    <div class="divider-options divider-top" v-if="active == 'top'">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Tipe</label>
        <select class="form-control" v-model="type_top">
          <option value="none">Tidak ada</option>
          <option value="arrow">arrow</option>
          <option value="book">book</option>
          <option value="clouds">clouds</option>
          <option value="curve-asymmetrical">curve-asymmetrical</option>
          <option value="curve">curve</option>
          <option value="drops">drops</option>
          <option value="mountains">mountains</option>
          <option value="opacity-fan">opacity-fan</option>
          <option value="opacity-tilt">opacity-tilt</option>
          <option value="pyramids">pyramids</option>
          <option value="split">split</option>
          <option value="tilt">tilt</option>
          <option value="triangle-asymmetrical">triangle-asymmetrical</option>
          <option value="triangle">triangle</option>
          <option value="wave-brush">wave-brush</option>
          <option value="waves-pattern">waves-pattern</option>
          <option value="waves">waves</option>
          <option value="zigzag">zigzag</option>
        </select>
      </div>
      <div v-if="type_top !== 'none'">
        <div class="icl-ed-field icl-ed-field--horizontal mb-3" v-if="topInvertable">
          <label class="form-label">Invert</label>
          <div class="field-switch">
            <label>
              <input type="checkbox" v-model="invert_top">
              <span class="field-switch-ui"></span>
            </label>
          </div>      
        </div>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Lebar</label>
          <div class="form-control-slider">
            <vue-slider
              :min="100"
              :max="300"
              :interval="1"
              v-model="width_top"></vue-slider>
            <input class="slider-val" @keypress="isNumber($event)" v-model="width_top">
          </div>
        </div>
        
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Tinggi</label>
          <div class="form-control-slider">
            <vue-slider
              :min="0"
              :max="300"
              :interval="1"
              v-model="height_top"></vue-slider>
            <input class="slider-val" @keypress="isNumber($event)" v-model="height_top">
          </div>
        </div>
        
        <div class="icl-ed-field icl-ed-field--horizontal mb-4">
          <label class="form-label">Warna</label>
          <div class="icl-color-picker" ref="colorpickertop">
            <div class="input-group">
              <div @click="showPicker('start')" class="input-group-prepend">
                <div class="input-group-text" :style="{'backgroundColor':color_top}"></div>
              </div>
              <input
                class="form-control"
                type="text"
                v-model="color_top"
                @click="showPicker('top')"
                >
            </div>
            <color-picker v-if="picker_top" :value="color_top" @input="updateColorTop" />
          </div>
        </div>
      </div>
    </div>

    <div class="divider-options divider-bottom" v-if="active == 'bottom'">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Tipe</label>
        <select class="form-control" v-model="type_bottom">
          <option value="none">Tidak ada</option>
          <option value="arrow">arrow</option>
          <option value="book">book</option>
          <option value="clouds">clouds</option>
          <option value="curve-asymmetrical">curve-asymmetrical</option>
          <option value="curve">curve</option>
          <option value="drops">drops</option>
          <option value="mountains">mountains</option>
          <option value="opacity-fan">opacity-fan</option>
          <option value="opacity-tilt">opacity-tilt</option>
          <option value="pyramids">pyramids</option>
          <option value="split">split</option>
          <option value="tilt">tilt</option>
          <option value="triangle-asymmetrical">triangle-asymmetrical</option>
          <option value="triangle">triangle</option>
          <option value="wave-brush">wave-brush</option>
          <option value="waves-pattern">waves-pattern</option>
          <option value="waves">waves</option>
          <option value="zigzag">zigzag</option>
        </select>
      </div>
      <div v-if="type_bottom !== 'none'">
        <div class="icl-ed-field icl-ed-field--horizontal mb-3" v-if="bottomInvertable">
          <label class="form-label">Invert</label>
          <div class="field-switch">
            <label>
              <input type="checkbox" v-model="invert_bottom">
              <span class="field-switch-ui"></span>
            </label>
          </div>      
        </div>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Lebar</label>
          <div class="form-control-slider">
            <vue-slider
              :min="100"
              :max="300"
              :interval="1"
              v-model="width_bottom"></vue-slider>
            <input class="slider-val" @keypress="isNumber($event)" v-model="width_bottom">
          </div>
        </div>
        
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Tinggi</label>
          <div class="form-control-slider">
            <vue-slider
              :min="0"
              :max="300"
              :interval="1"
              v-model="height_bottom"></vue-slider>
            <input class="slider-val" @keypress="isNumber($event)" v-model="height_bottom">
          </div>
        </div>
        
        <div class="icl-ed-field icl-ed-field--horizontal mb-4">
          <label class="form-label">Warna</label>
          <div class="icl-color-picker" ref="colorpickertop">
            <div class="input-group">
              <div @click="showPicker('start')" class="input-group-prepend">
                <div class="input-group-text" :style="{'backgroundColor':color_bottom}"></div>
              </div>
              <input
                class="form-control"
                type="text"
                v-model="color_bottom"
                @click="showPicker('bottom')"
                >
            </div>
            <color-picker v-if="picker_bottom" :value="color_bottom" @input="updateColorBottom" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Chrome } from 'vue-color';
export default {
  name: 'field-divider',
  props: {
    data: Object
  },
  components: {
    'color-picker': Chrome,
  },
  data() {
    return {
      active: 'top',
      picker_top : false,
      picker_bottom : false,
      type_top: 'none',
      type_bottom: 'none',
      invert_top: false,
      invert_bottom: false,
      color_top: '#ffffff',
      color_bottom: '#ffffff',
      width_top: 100,
      width_bottom: 100,
      height_top: 200,
      height_bottom: 200,
    }
  },
  computed: {
    topInvertable() {
      const notInvertable = ["mountains", "opacity-fan", "opacity-tilt", "tilt", "wave-brush", 'waves-pattern' , "zigzag"]
      return ( -1 == notInvertable.indexOf( this.type_top ) ) ? true : false
    },
    bottomInvertable() {
      const notInvertable = ["mountains", "opacity-fan", "opacity-tilt", "tilt", "wave-brush", 'waves-pattern' , "zigzag"]
      return ( -1 == notInvertable.indexOf( this.type_bottom ) ) ? true : false
    },
    value(){
      const notInvertable = ["mountains", "opacity-fan", "opacity-tilt", "tilt", "wave-brush", 'waves-pattern' , "zigzag"]

      // console.log( notInvertable.indexOf(this.type_top) );

      return {
        top: {
          type   : this.type_top,
          color  : this.color_top,
          width  : parseInt(this.width_top),
          height : parseInt(this.height_top),
          invert : ( notInvertable.indexOf(this.type_top) == -1 )  ? this.invert_top : false,
        },
        bottom: {
          type   : this.type_bottom,
          color  : this.color_bottom,
          width  : parseInt(this.width_bottom),
          height : parseInt(this.height_bottom),
          invert : ( notInvertable.indexOf(this.type_bottom) == -1 )  ? this.invert_bottom : false,
        }
      }
    }
  },
  watch: {
    $data: {
      handler(){
        this.data.value = this.value;
        this.$store.commit('dirty', true);
      },
      deep: true
    },
  },
  mounted(){
    this.type_top      = this.data.value.top.type;
    this.invert_top    = this.data.value.top.invert;
    this.color_top     = this.data.value.top.color;
    this.width_top     = this.data.value.top.width;
    this.height_top    = this.data.value.top.height;

    this.type_bottom   = this.data.value.bottom.type;
    this.invert_bottom = this.data.value.bottom.invert;
    this.color_bottom  = this.data.value.bottom.color;
    this.width_bottom  = this.data.value.bottom.width;
    this.height_bottom = this.data.value.bottom.height;
  },
  methods: {
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    showPicker(id){
      if ( this[`picker_${id}`] ) 
        this[`picker_${id}`] = false

      document.addEventListener('click', this.documentClick);
      this[`picker_${id}`] = true;
    },
    hidePicker(){
      document.removeEventListener('click', this.documentClick);
      this.picker_color = this.picker_top = this.picker_bottom = false;
    },
    documentClick(e){
      if ( ! e.target.closest('.icl-color-picker') )
        this.hidePicker();

    },
    updateColorTop(color){
      if ( color.rgba.a == 1 ) {
        this.color_top = color.hex
      } else {
        this.color_top = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
    updateColorBottom(color){
      if ( color.rgba.a == 1 ) {
        this.color_bottom = color.hex
      } else {
        this.color_bottom = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
  }
}
</script>

<style lang="less">
.divider-options{
  padding-top: 15px;
}
</style>