<template>
<div class="mlb-field mlb-field--background">
  <div class="icl-ed-field icl-ed-field--horizontal mb-4">
    <label class="form-label">Warna Latar</label>
    <div class="icl-color-picker" ref="colorpicker">
      <div class="input-group">
        <div @click="showPicker" class="input-group-prepend">
          <div class="input-group-text" :style="{'backgroundColor':background_color}"></div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="background_color"
          @click="showPicker"
          >
      </div>
      <color-picker v-if="is_active" :value="background_color" @input="updateColor" />
    </div>
  </div>
  <hr style="margin-top: 5px;">

  <div class="background-image">
    <label class="form-label">Gambar Latar</label>
    <div class="input-group">
      <input
        class="form-control"
        type="text"
        v-model="background_image" />
      <div class="input-group-append" @click="showMediaPicker">
        <span class="input-group-text"><i class="mdi mdi-image mr-2"></i>Media</span>
      </div>
    </div>
  </div>
  
  <div class="background-props" v-if="background_image">
    <div class="row mb-3">
      <div class="col">
        <label class="form-label mb-1">Posisi Latar</label>
        <div class="select is-fullwidth">
          <select class="form-control" v-model="background_position">
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
          <select class="form-control" v-model="background_size">
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
          <select class="form-control" v-model="background_repeat">
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
          <select class="form-control" v-model="background_attachment">
            <option value="scroll">Scroll</option>
            <option value="fixed">Fixed</option>
          </select>
        </div>
      </div>
    </div>
  </div>

</div>
</template>

<script>
import { Chrome } from 'vue-color';
export default {
  name: 'field-background',
  components: {
    'color-picker': Chrome,
  },
  props: {
    data: Object,
  },
  data(){
    return {
      is_active: false,
      background_color: 'transparent',
      background_image: '',
      background_position: 'center',
      background_size: 'cover',
      background_repeat: 'no-repeat',
      background_attachment: 'scroll',
    }
  },
  computed: {
    bgColor: function(){
      return {
        backgroundColor: this.data.value
      };
    }
  },
  watch: {
    'data.value': {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    },
    background_color(after,before){
      this.data.value.backgroundColor = after;
      this.$store.commit('dirty', true);
    },
    background_image(after,before){
      this.data.value.backgroundImage = after;
      this.$store.commit('dirty', true);
    },
    background_position(after,before){
      this.data.value.backgroundPosition = after;
      this.$store.commit('dirty', true);
    },
    background_size(after,before){
      this.data.value.backgroundSize = after;
      this.$store.commit('dirty', true);
    },
    background_repeat(after,before){
      this.data.value.backgroundRepeat = after;
      this.$store.commit('dirty', true);
    },
    background_attachment(after,before){
      this.data.value.backgroundAttachment = after;
      this.$store.commit('dirty', true);
    },
  },
  mounted(){
    this.background_color      = this.data.value.backgroundColor
    this.background_image      = this.data.value.backgroundImage
    this.background_position   = this.data.value.backgroundPosition
    this.background_size       = this.data.value.backgroundSize
    this.background_repeat     = this.data.value.backgroundRepeat
    this.background_attachment = this.data.value.backgroundAttachment
  },
  methods: {
    showMediaPicker(){
      this.$mediaPicker.show({
        title: "Select an image",
        selected: [this.background_image],
        multiple: false,
        onSelected: (value)=>{
          this.background_image = value[0]
        }
      })
    },
    onRemoveImage(){
      this.background_image = ""
    },
    showPicker(){
      if ( this.is_active ) 
        this.is_active = false

      document.addEventListener('click', this.documentClick);
      this.is_active = true;
    },
    hidePicker(){
      document.removeEventListener('click', this.documentClick);
      this.is_active = false;
    },
    togglePicker(){
      this.is_active = !this.is_active;
    },
    documentClick(e){
      var el = this.$refs.colorpicker,
        target = e.target;
      if (!el) return
      if ( el !== target && !el.contains(target) ) {
        this.hidePicker();
      }
    },
    updateColor(color){
      if ( color.rgba.a == 1 ) {
        this.background_color = color.hex
      } else {
        this.background_color = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
  }
}
</script>

<style lang="less">
.selected-images{
  display: flex;
  align-items: flex-start;
  flex-wrap: wrap;
}
.imageupload-ui{
  width: 100px;
  height: 100px;
  border: 2px solid #e3e3e3;
  border-radius: 4px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin-top: 15px;
  margin-right: 15px;
  img{
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  &:before{
    content: "add";
    font-family: "Material Icons";
    font-size: 48px;
    line-height: 1;
    color: #999;
  }
  &.has-image:before{ display: none }
}

.btn-remove-image{
  position: absolute;
  top: -10px;
  right: -10px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: red;
  color: white;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  .material-icons{
    font-size: 16px;
  }
}

.background-props{
  padding: 15px 20px;
  background-color: white;
  border: 1px solid #e3e3e3;
  border-right: none;
  border-left: none;
  margin: 20px -20px 0;
}
</style>