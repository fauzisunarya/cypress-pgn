<template>
<div class="icl-section__bg-overlay" :style="overlay"></div>
</template>

<script>
export default {
  name: 'overlay',
  props: ['data'],
  computed: {
    overlay() {
      const style = {};
      switch( this.data.type ) {
        case "color":
          style.backgroundColor = this.data.value;
          break;
        case "image":
          style.backgroundImage      = `url(${this.data.value.url})`;
          style.backgroundRepeat     = this.data.value.repeat;
          style.backgroundSize       = this.data.value.size;
          style.backgroundPosition   = this.data.value.position;
          style.backgroundAttachment = this.data.value.attachment;
          break;
        case "gradient":
          switch ( this.data.value.type ) {
            case 'linear':
              style.backgroundImage = `linear-gradient(${this.data.value.angle}deg, ${this.data.value.start} ${this.data.value.location}%, ${this.data.value.end} ${this.data.value.location2}%)`;
              break;
            case 'radial':
              style.backgroundImage = `radial-gradient(at ${this.data.value.position}, ${this.data.value.start} ${this.data.value.location}%, ${this.data.value.end} ${this.data.value.location2}%)`;
              break;
          }
          break;
        default:
          style.backgroundColor= this.data;
          break;
      }

      style.opacity = this.data.opacity/100;

      return style;
    }
  }
}
</script>