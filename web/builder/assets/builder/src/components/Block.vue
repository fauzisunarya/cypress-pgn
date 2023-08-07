<template>
  <!-- eslint-disable vue/no-unused-components -->
  <v-runtime-template :template="template"></v-runtime-template>
</template>

<script>
import VRuntimeTemplate from "v-runtime-template"
import GalleryGrid from '@/components/GalleryGrid'
import MasonryGrid from '@/components/MasonryGrid'
import Carousel from '@/components/Carousel'
import Button from '@/components/elements/Button'
import Overlay from '@/components/elements/Overlay'
import NavbarMenu from '@/components/elements/NavbarMenu'
import InlineEditor from '@/components/elements/InlineEditor'
import Sortable from '@/components/elements/Sortable'
import SortableItem from '@/components/elements/SortableItem'
import Icon from '@/components/elements/Icon'
import Divider from '@/components/elements/Divider'
import DividerShape from '@/components/elements/DividerShape'
import ContactForm from '@/components/ContactForm'
import Post from '@/components/elements/Post'
import Post2 from '@/components/elements/Post2'
import Post3 from '@/components/elements/Post3'
import Post4 from '@/components/elements/Post4'
import Catalog from '@/components/elements/Catalog'
import Catalog2 from '@/components/elements/Catalog2'
import Catalog3 from '@/components/elements/Catalog3'
import Catalog4 from '@/components/elements/Catalog4'
import Catalog5 from '@/components/elements/Catalog5'
import Slider6 from '@/components/elements/Slider6'
import QuantityNavigation from '@/components/elements/QuantityNavigation'

export default {
  name: 'Block',
  props: {
    model: Object,
  },
  components: {
    VRuntimeTemplate,
    GalleryGrid,
    MasonryGrid,
    Carousel,
    Button,
    NavbarMenu,
    Overlay,
    InlineEditor,
    Sortable,
    SortableItem,
    Icon,
    Divider,
    DividerShape,
    ContactForm,
    Post,
    Post2,
    Post3,
    Post4,
    Catalog,
    Catalog2,
    Catalog3,
    Catalog4,
    Catalog5,
    Slider6,
    QuantityNavigation,
  },
  directives: {
    linkoption: {
      bind: function(el, binding) {
        el.addEventListener( 'click', function(){
          console.log(binding.value)
        } )
      }
    }
  },
  
  data(){
    return {
      valid: false
    }
  },
  mounted(){
    var tmpl = document.getElementById(this.model.template);
    this.valid = (tmpl !== null) ? true : false
  },
  computed: {
    template: function(){
      return this.model.template
    },
    data: function(){
      return {
        ...this.model.data
      }
    },
    style: function(){
      return {
        ...this.model.style
      }
    },
    id: function(){
      return this.model.id
    },
  },
};
</script>