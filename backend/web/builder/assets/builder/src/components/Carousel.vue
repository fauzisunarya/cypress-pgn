<template>
  <div class="icl-carousel-block">
    <div class="icl-carousel-block__item-template">
      <slot/>
    </div>

    <div class="icl-carousel"
      :data-items="items"
      :data-nav="nav"
      :data-controls="controls"
      >
      <slot/>
    </div>
    
  </div>
</template>

<script>
import EventBus from '@/components/EventBus'
export default {
  name : 'carousel',
  props: {
    id          : String,
    items       : Number,
    slides      : Array,
    nav         : {type: Boolean, default: true},
    mode        : {type: String, default: 'carousel'},
    controls    : {type: Boolean, default: true},
    gutter      : {type: Number, default: 0},
    center      : {type: Boolean, default: false},
    slideBy     : {type: [Number,String], default: 'page'},
    edgePadding : {type:Number, default: 0},
    axis        : {type:String, default: 'horizontal'}
  },
  watch: {
    slides: {
      handler() {
        EventBus.$emit('update-carousel', {
          id          : this.id,
          items       : this.items,
          mode        : this.mode,
          nav         : this.nav,
          controls    : this.controls,
          gutter      : this.gutter,
          center      : this.center,
          slideBy     : this.slideBy,
          edgePadding : this.edgePadding,
          axis        : this.axis
        })
      },
      deep: true
    },
    items(after) {
      EventBus.$emit('update-carousel', {
        id          : this.id,
        items       : after,
        nav         : this.nav,
        mode        : this.mode,
        controls    : this.controls,
        gutter      : this.gutter,
        center      : this.center,
        slideBy     : this.slideBy,
        edgePadding : this.edgePadding,
        axis        : this.axis,

      })
    },
    nav(after) {
      EventBus.$emit('update-carousel', {
        id          : this.id,
        items       : this.items,
        nav         : after,
        mode        : this.mode,
        controls    : this.controls,
        gutter      : this.gutter,
        center      : this.center,
        slideBy     : this.slideBy,
        edgePadding : this.edgePadding,
        axis        : this.axis,
      })
    },
    mode(after) {
      EventBus.$emit('update-carousel', {
        id          : this.id,
        items       : this.items,
        nav         : this.nav,
        mode        : after,
        controls    : this.controls,
        gutter      : this.gutter,
        center      : this.center,
        slideBy     : this.slideBy,
        edgePadding : this.edgePadding,
        axis        : this.axis,
      })
    },
    controls(after) {
      EventBus.$emit('update-carousel', {
        id          : this.id,
        items       : this.items,
        nav         : this.nav,
        mode        : this.mode,
        controls    : after,
        gutter      : this.gutter,
        center      : this.center,
        slideBy     : this.slideBy,
        edgePadding : this.edgePadding,
        axis        : this.axis,
      })
    }
  },
  mounted(){
    EventBus.$emit('carousel-mounted', {
      id          : this.id,
      items       : this.items,
      nav         : this.nav,
      mode        : this.mode,
      controls    : this.controls,
      gutter      : this.gutter,
      center      : this.center,
      slideBy     : this.slideBy,
      edgePadding : this.edgePadding,
      axis        : this.axis,
    });
  }
}
</script>

<style>
  
</style>