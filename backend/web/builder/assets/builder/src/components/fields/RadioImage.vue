<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="field-image-radio">
      <label v-for="(option,index) in data.options" :key="index">
        <input :name="data.label" type="radio" :value="option.value" v-model="data.value" />
        <img :src="option.image">
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'field-radio-image',
  props: {
    data: Object
  },
  watch: {
    'data.value' : {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
}
</script>

<style lang="less">
  .field-image-radio{
  user-select: none;
    input{
      display: none;

      &:checked + img{
        border-color: #6ab04c;
      }
    }
    img{
      width: 100px;
      height: 100px;
      border: 5px solid transparent;
      cursor: pointer;
    }
  }
</style>