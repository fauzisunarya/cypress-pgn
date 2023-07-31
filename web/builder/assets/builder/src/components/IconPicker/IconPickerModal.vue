<template>
<transition name="fade">
  <div class="icon-picker" v-if="visible">
    <div class="icon-picker__window">
      <div class="icon-picker__header">
        <h2>{{title}}</h2>
        <button @click="onClose"><i class="mdi mdi-close"></i></button>
      </div>
      <div class="icon-picker__search">
        <div class="control has-icons-left">
          <input type="text" class="input is-fullwidth" v-model="filter" placeholder="Cari ikon berdasarkan nama" />
          <span class="icon"><i class="mdi mdi-search"></i></span>
        </div>
      </div>
      <div class="icon-picker__grid" v-if="icons.length">
        <label class="icon-picker__item" v-for="icon in icons" :key="icon">
          <input type="radio" :value="icon" v-model="selected" />
          <i :class="`${icon}`"></i>
        </label>
      </div>
      <div class="icon-picker__empty" v-else>
        Icon not found for that keyword
      </div>
      <div class="icon-picker__action">
        <button class="btn btn-primary" @click="onSelect">Gunakan ikon ini</button>
      </div>
    </div>
  </div>
</transition>
</template>

<script>
import Modal from './index'
import FontAwesome from './fontawesome'
export default {
  name: 'icon-picker-modal',
  data(){
    return {
      title: 'Select an icon',
      visible: false,
      selected: '',
      filter: '',
      onSelected: {}
    }
  },
  computed: {
    icons(){
      return FontAwesome.filter( i => i.indexOf(this.filter.toLowerCase()) !== -1 )
    }
  },
  methods: {
    onSelect(){
      if ( typeof this.onSelected === 'function' ) {
        this.onSelected( this.selected )
        this.onClose();
      } else {
        this.onClose();
      }
    },
    onClose(){
      this.visible = false
      this.filter = ''
    },
    show(params){
      this.visible    = true
      this.title      = params.title
      this.selected   = params.selected
      this.onSelected = params.onSelected
    },
  },
  beforeMount() {
    Modal.event.$on('showIconPicker', (params) => {
      this.show(params)
    })
  }
}
</script>

<style lang="less" scoped>
.icon-picker{
  position: fixed;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,.7);
  left: 0;
  top: 0;
  z-index: 9999;
  &__window{
    width: 768px;
    background-color: white;
    box-shadow: 0 5px 15px rgba(0,0,0,.3);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    margin: auto;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
  }
  &__header{
    padding: 15px;
    background-color: white;
    position: relative;
    border-bottom: 1px solid #e3e3e3;
    h2{
      font-size: 18px;
      font-weight: 700;
      margin: 0;
      line-height: 1;
    }
    button{
      position: absolute;
      top:10px;
      right: 10px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: darken(red,10%);
      border: none;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 15px;
    }
  }
  
  &__grid{
    display: flex;
    flex-wrap: wrap;
    max-height: 320px;
    overflow: auto;
    justify-content: flex-start;
  }
  &__item{
    // flex: 1;
    font-size: 24px;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
    justify-content: center!important;
    cursor: pointer;
    margin-bottom: 0;
    input{
      display: none;
    }
    i{
      display: block;
      width: 48px;
      height: 48px;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    input:checked + i{
      position: relative;
      z-index: 1;
      box-shadow: inset 0 0 0 2px blue;
      color: blue;
    }
  }
  &__search{
    padding: 10px;
    border-bottom: 1px solid #e3e3e3;
    .control{
      display: flex;
      align-items: center;
    }
    input{
      flex: 1;
      border: none;
    }
  }
  &__action{
    padding: 15px;
    text-align: right;
    border-top: 1px solid #e3e3e3;
  }
  &__empty{
    padding: 20px;
    text-align: center;
  }
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>