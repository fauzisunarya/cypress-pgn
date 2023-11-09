<template>
<div class="mlb-field mlb-field--overlay">
  <div class="icl-ed-field icl-ed-field--horizontal mb-4">
    <label class="form-label">Tipe Overlay Latar</label>
    <div class="field-select">
      <select class="form-control" v-model="type">
        <option value="color">Warna</option>
        <option value="gradient">Gradasi</option>
        <option value="image">Gambar</option>
      </select>
    </div>
  </div>

  <div class="field-overlay color" v-show="type == 'color'">
    <div class="icl-ed-field icl-ed-field--horizontal">
      <label class="form-label">Warna Overlay</label>
      <div class="icl-color-picker" ref="colorpicker">
        <div class="input-group">
          <div @click="showPicker('color')" class="input-group-prepend">
            <div class="input-group-text" :style="{'backgroundColor':color}"></div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="color"
            @click="showPicker('color')"
            >
        </div>
        <color-picker v-if="picker_color" :value="color" @input="updateColor" />
      </div>
    </div>
  </div>

  <div class="field-overlay image" v-show="type == 'image'">
    <div class="background-image">
      <label class="form-label">Gambar Overlay</label>
      <div class="input-group">
        <input
          class="form-control"
          type="text"
          v-model="image.url" />
        <div class="input-group-append" @click="showMediaPicker">
          <span class="input-group-text"><i class="mdi mdi-image mr-2"></i>Media</span>
        </div>
      </div>
    </div>
    
    <div class="background-props" v-if="image.url">
      <div class="row mb-3">
        <div class="col">
          <label class="form-label mb-1">Posisi Latar</label>
          <div class="select is-fullwidth">
            <select class="form-control" v-model="image.position">
              <option value="top left">Atas Kiri</option>
              <option value="top center">Atas Tengah</option>
              <option value="top right">Atas Kanan</option>
              <option value="center left">Tengah Kiri</option>
              <option value="center">Tengah</option>
              <option value="center right">Tengah Kanan</option>
              <option value="bottom left">Bawah Kiri</option>
              <option value="bottom center">Bawah Tengah</option>
              <option value="bottom right">Bawah Kanan</option>
            </select>
          </div>
        </div>
        <div class="col">
          <label class="form-label mb-1">Ukuran Latar</label>
          <div class="select is-fullwidth">
            <select class="form-control" v-model="image.size">
              <option value="initial">Ukuran Orisinal</option>
              <option value="cover">Cover</option>
              <option value="contain">Contain</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label mb-1">Pengulangan Latar</label>
          <div class="select is-fullwidth">
            <select class="form-control" v-model="image.repeat">
              <option value="no-repeat">Jangan Diulangi</option>
              <option value="repeat">Ulangi</option>
              <option value="repeat-x">Ulangi horizontal</option>
              <option value="repeat-y">Ulangi vertical</option>
            </select>
          </div>
        </div>
        <div class="col">
          <label class="form-label mb-1">Penyematan Latar</label>
          <div class="select is-fullwidth">
            <select class="form-control" v-model="image.attachment">
              <option value="scroll">Scroll</option>
              <option value="fixed">Fixed</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="field-overlay gradient" v-show="type == 'gradient'">
    <div class="icl-ed-field icl-ed-field--horizontal mb-4">
      <label class="form-label">Warna Awal</label>
      <div class="icl-color-picker" ref="colorpickerstart">
        <div class="input-group">
          <div @click="showPicker('start')" class="input-group-prepend">
            <div class="input-group-text" :style="{'backgroundColor':gradient.start}"></div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="gradient.start"
            @click="showPicker('start')"
            >
        </div>
        <color-picker v-if="picker_start" :value="gradient.start" @input="updateColorStart" />
      </div>
    </div>
    <div class="icl-ed-field icl-ed-field--horizontal mb-4">
      <label class="form-label">Lokasi warna awal</label>
      <div class="form-control-slider">
        <vue-slider
          :min="0"
          :max="100"
          :interval="1"
          v-model="gradient.location"></vue-slider>
        <input class="slider-val" @keypress="isNumber($event)" v-model="gradient.location">
      </div>
    </div>
    <div class="icl-ed-field icl-ed-field--horizontal mb-4">
      <label class="form-label">Warna Akhir</label>
      <div class="icl-color-picker" ref="colorpickerend">
        <div class="input-group">
          <div @click="showPicker('end')" class="input-group-prepend">
            <div class="input-group-text" :style="{'backgroundColor':gradient.end}"></div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="gradient.end"
            @click="showPicker('end')"
            >
        </div>
        <color-picker v-if="picker_end" :value="gradient.end" @input="updateColorEnd" />
      </div>
    </div>
    <div class="icl-ed-field icl-ed-field--horizontal mb-4">
      <label class="form-label">Lokasi warna akhir</label>
      <div class="form-control-slider">
        <vue-slider
          :min="0"
          :max="100"
          :interval="1"
          v-model="gradient.location2"></vue-slider>
        <input class="slider-val" @keypress="isNumber($event)" v-model="gradient.location2">
      </div>
    </div>
    
    <div class="icl-ed-field icl-ed-field--horizontal mb-4">
      <label class="form-label">Tipe Gradasi</label>
      <div class="field-select">
        <select class="form-control" v-model="gradient.type">
          <option value="linear">Linear</option>
          <option value="radial">Radial</option>
        </select>
      </div>
    </div>

    <div class="icl-ed-field icl-ed-field--horizontal mb-4" v-if="gradient.type=='linear'">
      <label class="form-label">Sudut</label>
      <div class="form-control-slider">
        <vue-slider
          :min="0"
          :max="360"
          :interval="1"
          v-model="gradient.angle"></vue-slider>
        <input class="slider-val" @keypress="isNumber($event)" v-model="gradient.angle">
      </div>
    </div>

    <div class="icl-ed-field icl-ed-field--horizontal mb-4" v-if="gradient.type=='radial'">
      <label class="form-label">Posisi Gradasi</label>
      <div class="field-select">
        <div class="select is-fullwidth">
          <select class="form-control" v-model="gradient.position">
            <option value="top left">Atas Kiri</option>
            <option value="top center">Atas Tengah</option>
            <option value="top right">Atas Kanan</option>
            <option value="center left">Tengah Kiri</option>
            <option value="center center">Tengah</option>
            <option value="center right">Tengah Kanan</option>
            <option value="bottom left">Bawah Kiri</option>
            <option value="bottom center">Bawah Tengah</option>
            <option value="bottom right">Bawah Kanan</option>
          </select>
        </div>
      </div>
    </div>
    
    
  </div>

  <div class="icl-ed-field icl-ed-field--horizontal mb-4">
    <label class="form-label">Transparansi Overlay</label>
    <div class="form-control-slider">
      <vue-slider
        :min="0"
        :max="100"
        :interval="1"
        v-model="opacity"></vue-slider>
      <input class="slider-val" @keypress="isNumber($event)" v-model="opacity">
    </div>
  </div>
</div>
</template>

<script>
import { Chrome } from 'vue-color';
export default {
  name: 'field-overlay',
  components: {
    'color-picker': Chrome,
  },
  props: ['data'],
  data(){
    return {
      picker_color : false,
      picker_start : false,
      picker_end   : false,
      type         : 'color',
      color        : 'rgba(255,255,255,0)',
      image: {
        url        : '',
        position   : 'center',
        size       : 'cover',
        repeat     : 'no-repeat',
        attachment : 'scroll',
      },
      gradient: {
        type      : 'linear',
        start     : 'black',
        location  : 0,
        end       : 'white',
        location2 : 100,
        angle     : 0,
        position  : 'center center'
      },
      opacity: 50
    }
  },
  watch:{
    $data: {
      handler(value){
        this.data.value = this.value;
        this.$store.commit('dirty', true);
      },
      deep: true
    },
    gradient : {
      handler( after ){
        if ( after.location > 100 ) this.gradient.location = 100;
        if ( after.location < 0 ) this.gradient.location = 0;
        if ( after.location == '' ) this.gradient.location = 0;

        if ( after.location2 > 100 ) this.gradient.location2 = 100;
        if ( after.location2 < 0 ) this.gradient.location2 = 0;
        if ( after.location2 == '' ) this.gradient.location2 = 0;

        if ( after.angle > 360 ) this.gradient.angle = 360;
        if ( after.angle < 0 ) this.gradient.angle = 0;
        if ( after.angle == '' ) this.gradient.angle = 0;

      },
      deep: true
    },
    opacity(after, before) {
      if ( after > 100 ) this.opacity = 100
      if ( after < 0 ) this.opacity = 0
      if ( after == '' ) this.opacity = 0
    }
  },
  computed: {
    value(){
      const data = {}
      data.type = this.type;
      switch ( data.type ) {
        case 'color':
          data.value = this.color
          break;
        case 'image':
          data.value = {
            url        : this.image.url,
            position   : this.image.position,
            size       : this.image.size,
            repeat     : this.image.repeat,
            attachment : this.image.attachment,
          }
          break;
        case 'gradient':
          data.value = {
            start    : this.gradient.start,
            end      : this.gradient.end,
            angle    : this.gradient.angle,
            location : this.gradient.location,
            location2 : this.gradient.location2,
            type     : this.gradient.type,
            position  : this.gradient.position
          }
          break;
      }

      data.opacity = this.opacity;

      return data;
    }
  },
  mounted(){
    switch( this.data.value.type ) {
      case "color":
        this.type = "color";
        this.color = this.data.value.value
        this.opacity = this.data.value.opacity;
        break;
      case "image":
        this.type = "image";
        this.image= {
          url        : this.data.value.value.url,
          position   : this.data.value.value.position,
          size       : this.data.value.value.size,
          repeat     : this.data.value.value.repeat,
          attachment : this.data.value.value.attachment,
        }
        this.opacity = this.data.value.opacity;
        break;
      case "gradient":
        this.type ="gradient"
        this.gradient = {
          start    : this.data.value.value.start,
          end      : this.data.value.value.end,
          angle    : this.data.value.value.angle,
          location : this.data.value.value.location,
          location2 : this.data.value.value.location2,
          type     : this.data.value.value.type,
          position  : this.data.value.value.position
        }
        this.opacity = this.data.value.opacity;
        break;
      default:
        this.type = "color";
        this.color = this.data.value
    }
  },
  methods: {
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();;
      } else {
        return true;
      }
    },
    showMediaPicker(){
      this.$mediaPicker.show({
        title: "Select an image",
        selected: [this.image.url],
        multiple: false,
        onSelected: (value)=>{
          this.image.url = value[0]
        }
      })
    },
    onRemoveImage(){
      this.image.url = ""
    },
    showPicker(id){
      if ( this[`picker_${id}`] ) 
        this[`picker_${id}`] = false

      document.addEventListener('click', this.documentClick);
      this[`picker_${id}`] = true;
    },
    hidePicker(){
      document.removeEventListener('click', this.documentClick);
      this.picker_color = this.picker_start = this.picker_end = false;
    },
    documentClick(e){
      if ( ! e.target.closest('.icl-color-picker') )
        this.hidePicker();

    },
    updateColor(color){
      if ( color.rgba.a == 1 ) {
        this.color = color.hex
      } else {
        this.color = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
    updateColorStart(color){
      if ( color.rgba.a == 1 ) {
        this.gradient.start = color.hex
      } else {
        this.gradient.start = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
    updateColorEnd(color){
      if ( color.rgba.a == 1 ) {
        this.gradient.end = color.hex
      } else {
        this.gradient.end = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
  }
}
</script>

<style lang="less">
.field-overlay{
  margin: 0 -20px 20px;
  border-top: 1px solid #e3e3e3;
  border-bottom: 1px solid #e3e3e3;
  padding: 20px;
  background: #fbfbfb;
}
</style>
