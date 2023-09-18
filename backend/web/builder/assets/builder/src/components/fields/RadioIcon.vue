<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="field-icon-radio">
      <label v-for="(option,index) in data.options" :key="index">
        <input type="radio" :value="option.value" v-model="data.value" />
        <i :class="`mdi mdi-${option.icon}`"></i>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'field-radio-icon',
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
.field-icon-radio{
  display: flex;

  input{
    display: none;

    &:checked + .mdi{
      background-color: var(--blue);
      color: white;
      border-color: #6ab04c;
      // box-shadow: 0 0 0 2px #6ab04c;
    }
  }

  label{
    cursor: pointer;
    user-select: none;
    margin: 0!important;

    &:first-child .mdi{
      border-top-left-radius: 3px;
      border-bottom-left-radius: 3px;
    }

    &:last-child .mdi{
      border-right: none;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 3px;
    }
  }

  .mdi{
    margin: 0;
    padding: 5px;
    box-shadow: 0 0 0 2px #e3e3e3;
    background-color: white;
    display: flex;
    align-items: center;
    width: 40px;
    justify-content: center;
    transition: .3s ease
    
  }
}
</style>