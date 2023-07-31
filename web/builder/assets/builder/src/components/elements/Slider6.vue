<template>
  <!-- spy lebar 100% kasih container dg width 100% -->
  <div class="angga-slide-container">
    <!-- widthny kecil -->
    <div class="angga-slide-container2">
      <!-- kasih flex spy nyamping -->
      <div
        class="angga-slide-container3"
        v-bind:style="{ transform: `translateX(${-250 * counter}px)` }"
      >
        <img
          class="angga-img-slide"
          v-for="(image, index) in images"
          :key="image + index"
          :src="image"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Slider6",
  data() {
    return {
      counter: 0,
    };
  },
  props: {
    images: {
      type: Array,
      required: true,
    },
  },
  methods: {
    nextSlide: function() {
      if (this.counter == this.images.length - 1) {
        this.counter = 0;
      } else {
        this.counter++;
      }
    },
  },
  mounted: function() {
    this.$nextTick(function() {
      setInterval(this.nextSlide, 3000);
    });
  },
  beforeDestroy: function() {
    clearInterval(setInterval(this.nextSlide, 3000));
  },
};
</script>

<style>
.angga-img-slide {
  width: 200px;
  margin: 0 25px;
}

.angga-slide-container {
  width: 100%;
  overflow: hidden;
}

.angga-slide-container2 {
  width: 250px;
}

.angga-slide-container3 {
  display: flex;
  align-items: center;
  transition: 1s;
  width: 100vw;
}
</style>
