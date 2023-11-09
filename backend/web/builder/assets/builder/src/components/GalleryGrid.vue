<template>
  <isotope 
    ref="masonry"
    class="image-gallery"
    v-images-loaded:on.progress="updateLayout"
    :class="{
      'image-gallery--masonry': layout == 'masonry',
      'image-gallery--grid': layout == 'grid'
    }"
    :options='{layout: "masonry"}'
    :list="images"
    :style="{'--border-radius': `${radius}px`}"
    >
    <div
      class="image-item"
      v-for="(image,index) in images"
      :key="`image-${index+1}`"
      :style="{padding: `${gutter/2}px`}">
      <span class="image-item-ratio"><img :src="image"></span>
    </div>
  </isotope>
</template>

<script>
import isotope from 'vueisotope'
import EventBus from '@/components/EventBus'
export default {
  name: 'gallery-grid',
  components: {
    isotope
  },
  props: {
    images: Array,
    layout: String,
    radius: Number,
    gutter: Number,
  },
  methods: {
    updateLayout(){
      this.$refs.masonry.layout('masonry')
    }
  },
  mounted(){
    EventBus.$on('previewSize', ()=>{
      setTimeout(()=>{
        this.updateLayout();
      },500)
    });
    EventBus.$on('previewMode', ()=>{
      setTimeout(()=>{
        this.updateLayout();
      },500)
    });
  }
} 
</script>

<style lang="less">
  
</style>