<template>
  <div class="icl-ed-field">
    <label v-if="data.label" class="form-label">{{data.label}}</label>
    <div class="field-repeatable">
      <draggable
        class="repeatable-field"
        v-if="data.value.length"
        :list="data.value"
        group="structure"
        handle=".repeatable-field-item__handle"
        v-bind="dragOptions"
        @start="drag = true"
        @end="endDragging"
        >
        <!-- <transition-group type="transition" :name="!drag ? 'flip-list': null"> -->
          <div class="repeatable-field-item" v-for="(item, index) in data.value" :key="`key-${index}`">
            <div class="repeatable-field-item__header" @click="toggleActiveItem(index)">
              <div class="repeatable-field-item__handle"><i class="mdi mdi-drag_handle"></i></div>
              <span class="repeatable-field-item__title" v-if="data.item_title && data.item_title == 'button'">{{stripTags(item[data.item_title].value.label)}}</span>
              <span class="repeatable-field-item__title" v-else-if="data.item_title">{{stripTags(item[data.item_title].value)}}</span>
              <span class="repeatable-field-item__title" v-else>{{data.label}} item</span>
              <button class="repeatable-field-item__delete" @click.prevent="removeRepeatableItem(index)" data-tooltip="Hapus Item"><i class="mdi mdi-delete"></i></button>
            </div>
            <div class="repeatable-field-item__content" v-if="activeItem == index">
              <div class="form-group" v-for="(item_field,index) in item" :key="`field-${index}`">
                <component :is="`field-${item_field.type}`" :data="item_field" ></component>
              </div>
            </div>
          </div>
        <!-- </transition-group> -->
      </draggable>
      
      <button
        class="btn btn-outline-secondary repeatable-field-item__add"
        @click="addRepeatableItem"
      >
        <i class="mdi mdi-add"></i>
        Tambah Item
      </button>
    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import Text from '@/components/fields/Text'
import Slider from '@/components/fields/Slider'
import WYSWYG from "@/components/fields/wyswyg"
import Select from '@/components/fields/Select'
import Repeatable from '@/components/fields/Repeatable'
import RadioImage from '@/components/fields/RadioImage'
import RadioIcon from '@/components/fields/RadioIcon'
import Switch from '@/components/fields/Switch'
import Image from '@/components/fields/Image'
import Color from '@/components/fields/Color'
import Icon from '@/components/fields/Icon'
import Spacer from '@/components/fields/Spacer'
// import Product from '@/components/fields/Product'
import Directional from '@/components/fields/Directional'
import Background from '@/components/fields/Background'
import Gallery from '@/components/fields/Gallery'
import Button from '@/components/fields/Button'

import cloneDeep from 'lodash/cloneDeep'


export default {
  name: 'field-repeatable',
  components: {
    draggable,
    'field-text': Text,
    'field-wyswyg': WYSWYG,
    'field-select': Select,
    'field-repeatable': Repeatable,
    'field-radio-image': RadioImage,
    'field-radio-icon': RadioIcon,
    'field-switch': Switch,
    'field-image': Image,
    'field-color': Color,
    'field-icon': Icon,
    'field-slider': Slider,
    // 'field-product': Product,
    'field-spacer': Spacer,
    'field-directional': Directional,
    'field-background': Background,
    'field-gallery': Gallery,
    'field-button': Button,
  },
  props: {
    data: Object,
  },
  data(){
    return {
      drag: false,
      itemTemplate: null,
      activeItem: null
    }
  },
  mounted(){
    const defaultSetting = cloneDeep(this.data.settings)
    this.itemTemplate = defaultSetting;
  },
  computed: {
    dragOptions() {
      return {
        animation: 200,
        group: "structure",
        disabled: false,
        ghostClass: "ghost",
        fallbackAxis: 'y',
        forceFallback: true
      };
    },
  },
  watch: {
    'data.value': {
      handler(after, before){
        // console.log("Value updated", after, before)
      }
    }
  },
  methods: {
    stripTags( value ) {
      return value.replace(/(<([^>]+)>)/ig,"");
    },
    toggleActiveItem(index){
      if (this.activeItem == index){
        this.activeItem = null
      } else {
        this.activeItem = index
      }
    },
    addRepeatableItem(){
      // console.log(this.itemTemplate)
      const limit = this.data.limit || 99999;
      if ( this.data.value.length == this.data.limit )
        return this.$swal({
            title: 'Item terbatas',
            text: `Anda hanya bisa menambahkan ${this.data.limit} item untuk blok ini`,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
          })
      this.data.value.push(cloneDeep(this.itemTemplate));
      this.activeItem = this.data.value.length - 1;
      this.$store.commit('dirty', true);
    },
    removeRepeatableItem(index){
      this.$swal({
        title: 'Hapus item ini?',
        text: "Anda tidak dapat mengembalikkannya lagi.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!'
      }).then((result) => {
        // console.log(result);
        if (result.value) {
          this.data.value.splice(index, 1);
          this.$store.commit('dirty', true);
        }
      });
    },
    endDragging(){
      this.drag = false;
      this.$store.commit('dirty', true);
    }
  }
}
</script>

<style lang="less">
.repeatable-field{
  box-shadow: 0 0 1px 1px rgba(0,0,0,.1);
}
.repeatable-field-item{
  border: 1px solid #aaa;

  &__header{
    border-bottom: 1px solid #aaa;
    display: flex;
    align-items: center;

    span{
      flex: 1;
      text-align: left;
      // text-transform: uppercase;
      font-weight: 700;
      padding-left: 15px;
      padding-right: 15px;
    }
  }
  &__content{
    background-color: lighten(#e9ecf1,5%);

    .form-group{
      margin-bottom: 0;
      padding: 10px 0;
      // &:not(:last-child){
      //   border-bottom: 1px solid rgba(0,0,0,.1);
      // }
      &:first-child{ padding-top: 0 }
      &:last-child{ padding-bottom: 0 }
    }

    .field-button{
      padding: 0;
      border: none;
    }
  }

  &:last-child{
    border-bottom-color: #aaa
  }
}
.repeatable-field-item__handle{
  cursor: move;
  border-right: 1px solid #e3e3e3!important;
}
.repeatable-field-item__title{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.repeatable-field{
  margin-bottom: 15px;

  &-item{
    border: 1px solid #e3e3e3;
    border-bottom: none;
    background-color: white;

    &:last-child{
      border-bottom: 1px solid #e3e3e3;
    }

    &__header{
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #e3e3e3;
      cursor: pointer;
    }
    &__handle,
    &__delete{
      padding: 10px;
      display: flex;
      align-items: center;
      border: none;
    }
    &__delete{
      color: darken(red, 10%);
      opacity: 0;
      transition: .3s ease;
      .material-icons{font-size: 18px;}
    }
    &:hover .repeatable-field-item__delete{
      opacity: 1;
    }
    &__add{
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #e3e3e3;
      text-align: center;
      justify-content: center;
    }
    &__content{
      padding: 15px;
    }
  }
}

</style>