<template>
<div
  class="icl-ed-field icl-ed-field--directional">
  <label class="form-label">{{data.label}}</label>
  <span class="form-control">
    <label>
        <input type="text" v-model="top"/>
        <span>Atas</span>
    </label>
    <label>
        <input type="text" v-model="right"/>
        <span>Kanan</span>
    </label>
    <label>
        <input type="text" v-model="bottom"/>
        <span>Bawah</span>
    </label>
    <label>
        <input type="text" v-model="left"/>
        <span>Kiri</span>
    </label>
    <label class="link-value">
      <input type="checkbox" v-model="linked" />
      <span><i class="mdi mdi-link"></i></span>
    </label>
  </span>
</div>
</template>

<script>
export default {
  name: 'field-directional',
  props: {
    data: Object
  },
  data(){
    return {
      linked: true,
      linkedValue: 0,
      values: {
        left: 0,
        top: 0,
        bottom: 0,
        right: 0
      }
    }
  },
  mounted(){
    const { top, left, right, bottom } = this.data.value
    if ( top == left == right == bottom ){
      this.linked = true
      this.linkedValud = top
    } else {
      this.linked = false
      this.values = {
        top, left, right, bottom
      }
    }
  },
  watch:{
    top(val){
      return this.data.value.top = val
    },
    right(val){
      return this.data.value.right = val
    },
    bottom(val){
      return this.data.value.bottom = val
    },
    left(val){
      return this.data.value.left = val
    },
    'data.value': {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
  computed:{
    top:{
      get(){ 
        if(this.linked)
          return this.linkedValue
        return this.values.top 
      },
      set(value){
        if ( this.linked )
          return this.linkedValue = value || 0
        else
          return this.values.top = value || 0
      }
    },
    right:{
      get(){ 
        if(this.linked)
          return this.linkedValue
        return this.values.right 
      },
      set(value){
        if ( this.linked )
          return this.linkedValue = value || 0
        else
          return this.values.right = value || 0
      }
    },
    bottom:{
      get(){ 
        if(this.linked)
          return this.linkedValue
        return this.values.bottom 
      },
      set(value){
        if ( this.linked )
          return this.linkedValue = value || 0
        else
          return this.values.bottom = value || 0
      }
    },
    left:{
      get(){ 
        if(this.linked)
          return this.linkedValue
        return this.values.left 
      },
      set(value){
        if ( this.linked )
          return this.linkedValue = value || 0
        else
          return this.values.left = value || 0
      }
    }
  }
}
</script>

<style lang="less">
.icl-ed-field--directional {
  .form-control{
    display: flex;
    width: 100%;
    padding-top: 10px;
    height: auto;
    border: none;
    padding: 0;
    background-color: transparent;
    box-shadow: 0 2px 2px rgba(0,0,0,.1);
    margin-bottom: 15px;
    input{
      width: 100%;
      text-align: center;
      border: none;
      font-size: inherit;
      padding: 5px 0;
      margin-bottom: 5px;
    }
    label{
      display: flex;
      flex-direction: column;
      flex: 1;
      box-shadow: 0 0 0 1px #e3e3e3;
      background-color: white;
      margin-bottom: 0!important;

      span{
        font-size: 12px;
        border-top: 1px solid #e3e3e3;
        display: block;
        width: 100%;
        text-align: center;
        padding: 5px;
        text-transform: uppercase;
      }

      &.link-value{
        flex-basis: 50px;
        flex-grow: 0;
        text-align: center;
        align-self: stretch;
        cursor: pointer;
        span{
          display: flex;
          width: 100%;
          flex: 1;
          border-top: none;
          align-items: center;
          justify-content: center;
          border-top-right-radius: 3px;
          border-bottom-right-radius: 3px;
        }
        input{
          display: none;
          &:checked + span{
            color: white;
            background-color: #228be6
          }
        }
      }
    }
  }
}
</style>