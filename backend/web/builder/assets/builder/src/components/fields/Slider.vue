<template>
<div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
  <label class="form-label">{{data.label}}</label>
  <div class="form-control-slider">
    <vue-slider
      :min="data.min"
      :max="data.max"
      :mark="true"
      :interval="data.step"
      v-model="data.value"></vue-slider>
    <input @keypress="isNumber($event)" class="slider-val" type="text" @change="inputChange" v-model="data.value">
  </div>
  <div class="form-notice" v-show="notice">{{notice}}</div>
</div>
</template>

<script>
export default {
  name: 'field-slider',
  props: {
    data: Object
  },
  data(){
    return {
      value: 0,
      notice: ''
    }
  },
  watch: {
    'data.value' : {
      handler(after, before){
        if( after !== before ) {
          this.$store.commit('dirty', true)
        }

      },
      deep: true
    }
  },
  methods: {
    inputChange(event){
      const value = parseInt(event.target.value);
      this.notice = ''
      if ( value > this.data.max ) {
        this.data.value= this.data.max;
        this.notice = `Nilai maksimal ${this.data.max}`
      }
      if ( value < this.data.min ) {
        this.data.value = this.data.min;
        this.notice = `Nilai minimal ${this.data.min}`
      }
      if ( value == '' || isNaN(value) ) {
        this.data.value= this.data.min;
        this.notice = `Nilai tidak boleh kosong, diatur ke nilai minimal: ${this.data.min}`
      }
    },
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
  }
}
</script>

<style>
.form-control-slider{
  min-width: 160px;
  display: flex;
  align-items: center;
}
.vue-slider{
  flex: 1;
}
.slider-val{
  width: 40px;
  font-weight: 700;
  text-align: right;
  background-color: #f3f3f3;
  border: 1px solid rgba(0,0,0,.1);
  border-radius: 3px;
  text-align: center;
  padding: 5px 3px;
  margin-left: 15px;
}
/* component style */
.vue-slider-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* rail style */
.vue-slider-rail {
  background-color: #ccc;
  border-radius: 15px;
}

/* process style */
.vue-slider-process {
  background-color: #3498db;
  border-radius: 15px;
  z-index: 0!important;
}

/* mark style */
.vue-slider-mark {
  z-index: 4;
}
.vue-slider-mark:first-child .vue-slider-mark-step, .vue-slider-mark:last-child .vue-slider-mark-step {
  display: none;
}
.vue-slider-mark-step {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: rgba(0, 0, 0, 0.16);
}
.vue-slider-mark-label {
  font-size: 14px;
  white-space: nowrap;
}
/* dot style */
.vue-slider-dot{
  z-index: 0!important;
}
.vue-slider-dot-handle {
  cursor: pointer;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #fff;
  box-sizing: border-box;
  box-shadow: 0.5px 0.5px 2px 1px rgba(0, 0, 0, 0.32);
}
.vue-slider-dot-handle-focus {
  box-shadow: 0px 0px 1px 2px rgba(52, 152, 219, 0.36);
}

.vue-slider-dot-handle-disabled {
  cursor: not-allowed;
  background-color: #ccc;
}

.vue-slider-dot-tooltip-inner {
  font-size: 14px;
  white-space: nowrap;
  padding: 2px 5px;
  min-width: 20px;
  text-align: center;
  color: #fff;
  border-radius: 5px;
  border-color: #3498db;
  background-color: #3498db;
  box-sizing: content-box;
}
.vue-slider-dot-tooltip-inner::after {
  content: "";
  position: absolute;
}
.vue-slider-dot-tooltip-inner-top::after {
  top: 100%;
  left: 50%;
  transform: translate(-50%, 0);
  height: 0;
  width: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px;
  border-top-color: inherit;
}
.vue-slider-dot-tooltip-inner-bottom::after {
  bottom: 100%;
  left: 50%;
  transform: translate(-50%, 0);
  height: 0;
  width: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px;
  border-bottom-color: inherit;
}
.vue-slider-dot-tooltip-inner-left::after {
  left: 100%;
  top: 50%;
  transform: translate(0, -50%);
  height: 0;
  width: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px;
  border-left-color: inherit;
}
.vue-slider-dot-tooltip-inner-right::after {
  right: 100%;
  top: 50%;
  transform: translate(0, -50%);
  height: 0;
  width: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px;
  border-right-color: inherit;
}

.vue-slider-dot-tooltip-wrapper {
  opacity: 0;
  transition: all 0.3s;
}
.vue-slider-dot-tooltip-wrapper-show {
  opacity: 1;
}

/*# sourceMappingURL=default.css.map */

</style>