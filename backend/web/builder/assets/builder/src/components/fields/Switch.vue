<template>
  <div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <label class="form-label">{{data.label}}</label>
    <div class="field-switch">
      <label>
        <input type="checkbox" v-model="data.value">
        <span class="field-switch-ui"></span>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'field-switch',
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
.field-switch{
  label{
    margin: 0!important;
  }
  input{
    display: none;
  }
  input:checked{
    + .field-switch-ui{
      background-color: var(--blue);
      border-color: var(--blue);

      &:before{
        left: 23px;
      }
    }

  }
  &-ui{
    width: 50px;
    height: 28px;
    border-radius: 18px;
    border: 2px solid #aaa;
    display: inline-block;
    position: relative;
    background-color: #eaeaea;
    transition: .3s ease;
    cursor: pointer;

    &:before{
      content: " ";
      width: 20px;
      height: 20px;
      background-color: white;
      border-radius: 50%;
      box-shadow: 0 1px 1px rgba(0,0,0,.1), 0 1px 3px rgba(0,0,0,.2);
      position: absolute;
      left: 2px;
      top: 2px;
      transition: .3s ease;
    }
  }
}
</style>