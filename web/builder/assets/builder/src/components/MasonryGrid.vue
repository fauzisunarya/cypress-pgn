<template>
  <isotope 
    ref="masonry"
    class="masonry-grid"
    v-images-loaded:on.progress="updateLayout"
    :options='{layout: "masonry"}'
    :list="items"
    >
    <slot/>
  </isotope>
</template>

<script>
import isotope from 'vueisotope'
import EventBus from '@/components/EventBus'
export default {
  name: 'masonry-grid',
  components: {
    isotope
  },
  props: {
    items: Array,
  },
  methods: {
    updateLayout(){
      if ( this.$refs.masonry )
        this.$refs.masonry.layout('masonry');
    }
  },
  mounted(){
    EventBus.$on('previewSize', ()=>{
      setTimeout(()=>{
        this.updateLayout();
      },500);
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