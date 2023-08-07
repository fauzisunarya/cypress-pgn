<template>
<div class="mlb-field mlb-field--button">

  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="form-label" v-if="data.label">{{data.label}}</div>
    <div class="button-preview">
      <button
        @click="edit_mode = !edit_mode"
        :href="url"
        :target="new_window"
        :class="`button ${size} ${style} ${corner}`"
        :style="{
          '--background': background,
          '--foreground' : color
        }">
        <span class="icon"  v-if="icon_position== 'left' && icon"><i :class="icon"></i></span>
        <span>Edit Tombol</span>
        <span class="icon"  v-if="icon_position== 'right' && icon"><i :class="icon"></i></span>
      </button>
    </div>
  </div>

  <div class="mlb-field--button-options" :class="{'active': edit_mode}" v-show="edit_mode">
    
    <div class="icl-ed-field icl-ed-field--horizontal mb-3">
      <label class="form-label">Tampilkan</label>
      <div class="field-switch">
        <label>
          <input type="checkbox" v-model="enable">
          <span class="field-switch-ui"></span>
        </label>
      </div>
    </div>

    <div :class="{'button-is-enabled': enable == true, 'button-is-disabled': enable == false}">
      <div class="icl-ed-field mb-3">
        <label class="form-label">Label</label>
        <input type="text" class="form-control" v-model="label">
      </div>

      <div class="icl-ed-field mb-3" v-if="!data.no_url">
        <label class="form-label">URL</label>
        <div class="input-group">
          <input type="text" class="form-control" v-model="url">
          <div class="input-group-append">
            <span @click="openLinkBuilder(url)" class="build-link input-group-text"><i class="mdi mdi-link"></i> Buat Link</span>
          </div>
        </div>
      </div>
      
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-2">
        <label class="form-label">Buka di tab baru?</label>
        <div class="field-switch">
          <label>
            <input type="checkbox" v-model="new_window">
            <span class="field-switch-ui"></span>
          </label>
        </div>
      </div>
      <hr style="margin-top: 0">
      <div class="row">
        <div class="col icl-ed-field mb-3">
          <label class="form-label">Warna Latar</label>
          <div class="icl-color-picker picker_background" ref="picker_background">
            <div class="input-group">
              <div @click="showPicker('background')" class="input-group-prepend">
                <div class="input-group-text" :style="{'backgroundColor':background}"></div>
              </div>
              <input
                class="form-control"
                type="text"
                v-model="background"
                @click="showPicker('background')"
                >
            </div>
            <color-picker v-if="active_picker == 'background'" :value="background" @input="updateBackground" />
          </div>
        </div>
        <div class="col icl-ed-field mb-3">
          <label class="form-label">Warna Teks</label>
          <div class="icl-color-picker picker_color" ref="picker_color">
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
            <color-picker v-if="active_picker == 'color'" :value="color" @input="updateColor" />
          </div>
        </div>
        
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Ukuran</label>
        <select v-model="size" class="form-control">
          <option value="is-small">Kecil</option>
          <option value="is-normal">Normal</option>
          <option value="is-medium">Sedang</option>
          <option value="is-large">Besar</option>
        </select>
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Gaya</label>
        <select v-model="style" class="form-control">
          <option value="is-fill">Warna Penuh</option>
          <option value="is-outline">Garis Pinggir</option>
        </select>
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Gaya Sudut</label>
        <select v-model="corner" class="form-control">
          <option value="is-square">Tajam</option>
          <option value="is-radius">Lengkung Kecil</option>
          <option value="is-rounded">Pill</option>
        </select>
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--icon icl-ed-field--horizontal mb-3">
        <div class="icon-field">
          <label class="form-label">Ikon</label>
          <div>
            <span class="icon-picker-btn" @click="selectIcon">
              <i v-if="icon" :class="icon"></i>
              <i v-else class="material-icons">add</i>
            </span>
            <button v-if="icon" class="icon-picker-clear" @click="clearInput"><i class="mdi mdi-close"></i></button>
          </div>
        </div>
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Posisi Ikon</label>
        <div class="field-icon-radio">
          <label>
            <input type="radio" value="left" v-model="icon_position" />
            <i class="mdi mdi-format_align_left"></i>
          </label>
          <label>
            <input type="radio" value="right" v-model="icon_position" />
            <i class="mdi mdi-format_align_right"></i>
          </label>
        </div>
      </div>
      <hr style="margin-top: 0">
      <div class="icl-ed-field icl-ed-field--horizontal mb-3">
        <label class="form-label">Facebook Pixel</label>
      </div>
      <div v-if="!facebook_pixel" class="icl-ed-field icl-ed-field--horizontal mb-3">
        <p><em>Anda belum melakukan integrasi <strong>Facebook Pixel</strong> ke dalam website. Silahkan lakukan integrasi <strong>Facebook Pixel</strong> melalui menu <strong>INTEGRASI</strong>, lalu pilih <strong>Facebook Pixel</strong></em></p>
      </div>
      <div v-else>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Onclick FB Event</label>
          <select v-model="fb_event" @change="showValue($event)" class="form-control">
            <option value="">No Event</option>
            <option value="AddPaymentInfo">AddPaymentInfo</option>
            <option value="AddToCart">AddToCart</option>
            <option value="AddToWishlist">AddToWishlist</option>
            <option value="CompleteRegistration">CompleteRegistration</option>
            <option value="Contact">Contact</option>
            <option value="CustomizeProduct">CustomizeProduct</option>
            <option value="Donate">Donate</option>
            <option value="FindLocation">FindLocation</option>
            <option value="InitiateCheckout">InitiateCheckout</option>
            <option value="Lead">Lead</option>
            <option value="Purchase">Purchase</option>
            <option value="Schedule">Schedule</option>
            <option value="Search">Search</option>
            <option value="SubmitApplication">SubmitApplication</option>
            <option value="ViewContent">ViewContent</option>
          </select>
        </div>
        <div v-if="is_purchase" class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Value</label>
          <input type="text" class="form-control" v-model="fb_value">
        </div>
        <div v-if="is_purchase" class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Currency</label>
          <select v-model="fb_currency" class="form-control">
            <option value="IDR">IDR</option>
            <option value="USD">USD</option>
          </select>
        </div>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Content Name</label>
          <input type="text" class="form-control" v-model="fb_content_name">
        </div>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Content ID</label>
          <input type="text" class="form-control" v-model="fb_content_ids">
        </div>
        <div class="icl-ed-field icl-ed-field--horizontal mb-3">
          <label class="form-label">Campaign URL</label>
          <input type="text" class="form-control" v-model="fb_campaign_url">
        </div>
      </div>
    </div>
    
  </div>

</div>
</template>

<script>
import { Chrome } from 'vue-color';

export default {
  name: 'field-button',
  components: {
    'color-picker': Chrome,
  },
  props: {
    data: Object,
  },
  data(){
    return {
      edit_mode       : false,
      active_picker   : null,
      enable          : true,
      url             : 'http://example.com',
      new_window      : true,
      label           : "Shop Now",
      background      : "default",
      color           : "#ffffff",
      size            : "normal", //[small, normal, medium, large]
      style           : "fill", //[fill, outline, ]
      corner          : "rounded", //[square, rounded, pill]
      icon            : "",
      icon_position   : "left",
      is_purchase     : false,
      fb_event        : null,
      fb_value        : null,
      fb_currency     : null,
      fb_content_name : null,
      fb_content_ids  : null,
      fb_campaign_url : null,
      facebook_pixel  : this.$store.state.project.meta.facebook_pixel,
    }
  },
  watch: {
    enable(after) {
      this.data.value.enable = after;
      
      this.$store.commit('dirty', true);
    },
    url(after) {
      this.data.value.url = after;
      this.$store.commit('dirty', true);
    },
    new_window(after) {
      this.data.value.new_window = after;
      this.$store.commit('dirty', true);
    },
    label(after) {
      this.data.value.label = after;
      this.$store.commit('dirty', true);
    },
    background(after) {
      this.data.value.background = after;
      this.$store.commit('dirty', true);
    },
    color(after) {
      this.data.value.color = after;
      this.$store.commit('dirty', true);
    },
    size(after) {
      this.data.value.size = after;
      this.$store.commit('dirty', true);
    },
    style(after) {
      this.data.value.style = after;
      this.$store.commit('dirty', true);
    },
    corner(after) {
      this.data.value.corner = after;
      this.$store.commit('dirty', true);
    },
    icon(after) {
      this.data.value.icon = after;
      this.$store.commit('dirty', true);
    },
    icon_position(after) {
      this.data.value.icon_position = after
      this.$store.commit('dirty', true);
    },
    fb_event(after) {
      this.data.value.fb_event = after
      this.$store.commit('dirty', true);
    },
    fb_value(after) {
      this.data.value.fb_value = after
      this.$store.commit('dirty', true);
    },
    fb_currency(after) {
      this.data.value.fb_currency = after
      this.$store.commit('dirty', true);
    },
    fb_content_name(after) {
      this.data.value.fb_content_name = after
      this.$store.commit('dirty', true);
    },
    fb_content_ids(after) {
      this.data.value.fb_content_ids = after
      this.$store.commit('dirty', true);
    },
    fb_campaign_url(after) {
      this.data.value.fb_campaign_url = after
      this.$store.commit('dirty', true);
    },
  },
  mounted(){
    this.url              = this.data.value.url
    this.new_window       = this.data.value.new_window
    this.enable           = this.data.value.enable
    this.label            = this.data.value.label
    this.background       = this.data.value.background
    this.color            = this.data.value.color
    this.size             = this.data.value.size
    this.style            = this.data.value.style
    this.corner           = this.data.value.corner
    this.icon             = this.data.value.icon
    this.icon_position    = this.data.value.icon_position
    this.fb_event         = this.data.value.fb_event
    this.fb_value         = this.data.value.fb_value
    this.fb_currency      = this.data.value.fb_currency
    this.fb_content_name  = this.data.value.fb_content_name
    this.fb_content_ids   = this.data.value.fb_content_ids
    this.fb_campaign_url  = this.data.value.fb_campaign_url
  },
  methods: {
    selectIcon(){
      this.active = true
      // this.$store.commit('iconPicker', true)
      const params = {
        title: 'Select an icon',
        selected: this.icon,
        onSelected: (icon) => {
          this.icon = icon
        }
      }
      this.$iconPicker.show(params)
    },
    showPicker(ref){
      this.active_picker = ref
      document.addEventListener('click', this.documentClick);
    },
    togglePicker(ref){
      this[ref] = !this[ref];
    },
    documentClick(e){
      if ( !e.target.closest('.icl-color-picker') ){
        this.active_picker = null
        document.removeEventListener('click', this.documentClick)
      }
    },
    updateBackground(color){
      if ( color.rgba.a == 1 ) {
        this.background = color.hex
      } else {
        this.background = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
    updateColor(color){
      if ( color.rgba.a == 1 ) {
        this.color = color.hex
      } else {
        this.color = `rgba(${color.rgba.r},${color.rgba.g},${color.rgba.b},${color.rgba.a})`
      }
    },
    clearInput(){
      this.icon = ''
    },
    openLinkBuilder(url){
      this.$linkBuilder.setDefaultData(url)
      this.$linkBuilder.show({
        onSelected: (value)=>{

          this.url = value;
          this.$store.commit('dirty', true);
        }
      })
    },
    showValue(event){
      console.log(event.target.value)
      if ( event.target.value == 'Purchase' ){
        this.is_purchase = true;
      } else if( event.target.value == null || event.target.value == '' ){
        this.is_purchase = false;
        this.fb_value = null;
        this.fb_currency = null;
        this.fb_content_name = null;
        this.fb_content_ids = null;
        this.fb_campaign_url = null;
      } else {
        this.is_purchase = false;
        this.fb_value = null;
      }
      this.$store.commit('dirty', true);
    }
  }
}
</script>

<style lang="less">
.mlb-field--button{
  h2{
    font-size: 16px;
  }
  .form-label{
    margin-bottom: 5px!important;
  }
  .mlb-field--button-options{
    padding: 20px;
    border: 1px solid #e3e3e3;
    background: white;
    margin-top: 15px;
    margin-left: -16px;
    margin-right: -16px;

    &.active{
      // box-shadow: inset 0 3px 10px rgba(0,0,0,.1);
    }
  }
  .icl-color-picker .form-control{
    width: 50px;
  }
  .icl-color-picker.picker_background .vc-chrome{
    right: auto;
    left: 0;
  }
}
.build-link{
  font-weight: 700;
}


.button{
  border: none;
  transition: .3s ease;
  font-weight: 700;
  cursor: pointer;
  justify-content: center;
  padding-bottom: calc(.375em - 1px);
  padding-left: .75em;
  padding-right: .75em;
  padding-top: calc(.375em - 1px);
  text-align: center;
  white-space: nowrap;

  &:hover{
    border: none;
    opacity: 1;
    box-shadow: 0 8px 20px -3px rgba(0,0,0,.4);
    transform: translateY(-4px);
  }
  &.is-fill{
    background-color: var(--background);
    color: var(--foreground);
  }
  &.is-outline{
    border-color: var(--background);
    color: var(--foreground);
    border: 2px solid;
    background-color: transparent;
    box-shadow: none!important;
    &:hover{
      border: 2px solid;
    }
  }

  &.is-square{
    border-radius: 0;
  }

  &.is-radius{
    border-radius: 5px;
  }

  .icon{
    font-size: 0.9em;
    opacity: 0.9;

    &:first-child:not(:last-child){
      margin-left: 0;
      margin-right: .5em;
    }
    &:last-child:not(:first-child){
      margin-left: .5em;
      margin-right: 0;
    }
  }

  .button-parent-style.is-fill &{
    background-color: var(--background);
    color: var(--foreground);
  }
  .button-parent-style.is-outline &{
    border-color: var(--background);
    color: var(--foreground);
    border: 2px solid;
    background-color: transparent;
    box-shadow: none!important;
    &:hover{
      border: 2px solid;
    }
  }
  .button-parent-style.is-rounded & {
    border-radius: 290486px;
    padding-left: 1em;
    padding-right: 1em;
  }
  .button-parent-style.is-radius & {
    border-radius: 5px;
    padding-left: 1em;
    padding-right: 1em;
  }
  .button-parent-style.is-sharp & {
    border-radius: 0;
  }

}
.button-preview{
  padding: 10px;
  background-image: linear-gradient(45deg,#efefef 25%,transparent 25%,transparent 75%,#efefef 75%,#efefef),linear-gradient(45deg,#efefef 25%,transparent 25%,transparent 75%,#efefef 75%,#efefef);
  background-position: 0 0, 10px 10px;
  background-size: 21px 21px;
  border: 1px solid #e3e3e3;

  .button.is-outline{
    filter: drop-shadow(0 1px 1px rgba(0,0,0,.3));
  }
}
.button-is-disabled{
  pointer-events: none;
  opacity: .5;
}
</style>