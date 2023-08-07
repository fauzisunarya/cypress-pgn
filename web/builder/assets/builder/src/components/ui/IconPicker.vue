<template>
<div class="icl-icon-picker" v-if="active">
  <div class="icl-icon-picker__wrap">
    <div class="icl-icon-picker__header">
      <input type="text" v-model="iconFilter" placeholder="Cari ikon berdasar nama">
      <button @click="closeIconPicker"><i class="mdi mdi-close"></i></button>
    </div>
    <div class="icl-icon-picker__content">
      <div class="icl-icon-picker__grid">
        <button 
          v-for="icon in icons"
          :key="icon"
          :title="icon"
          :value="icon" 
          :class="{'is-active':value == icon}"
          @click="setIconPicker(icon)"
          ><i :class="icon"></i></button>
      </div>
    </div>
  </div>
</div>
</template>

<style lang="less">
.icl-icon-picker{
  position: relative;
  top: 100%;
  width: 100%;
  height: 380px;
  left: 0;
  right: 0;
  background-color: white;
  box-shadow: 0 5px 20px -5px rgba(0,0,0,.4);
  border-radius: 5px;
  margin-top: 20px;

  &:before{
   content:" ";
   width: 0;
   height: 0;
   border-style: solid;
   border-width: 10px;
   border-color: transparent transparent #2c3e50;
   position: absolute;
   bottom: 100%;
   right: 32px;
  }

  &__wrap{
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    flex-direction: column;
  }

  &__header{
    padding: 10px;
    background-color: #2c3e50;
    color: white;
    display: flex;
    align-items: center;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;


    input{
      flex: 1;
      padding: 10px;
      border-radius: 5px;
      border: none;
      background-color: transparent;
      color: white;
      outline: onne;

      &:placeholder{
        color: white
      }
    }
    button{
      display: flex;
      border: none;
      background-color: transparent;
      color: white;
    }
  }
  &__content{
    padding: 15px;
    overflow: auto;
    margin-bottom: 15px;
    flex: 1;
  }
  &__grid{
    display: flex;
    flex-wrap: wrap;
    overflow: auto;

    button{
      width: 48px;
      height: 48px;
      outline: none;
      transition: .05s ease;
      font-size: 18px;
      border: none;
      border-radius: 5px;

      &:hover, &.is-active{
        background-color: var(--primary);
        color: white;
      }
    }
  }
}
</style>

<script>
import FontAwesome from "@/components/fontawesome-5"
export default{
   name: 'icon-picker',
  props: {
    value: String,
      active: Boolean
  },
  data(){
    return {
      iconFilter: '',
      fa: FontAwesome
    }
  },
  computed: {
    icons(){
        return this.fa.filter(item=>item.indexOf(this.iconFilter) !== -1);
      },
  },
  methods: {
    setIconPicker(value){
      // this.$store.commit('iconPicker',false);
      this.$emit('iconPicked',value)
         this.$emit('closeIconPicker')
    },
      closeIconPicker(){
        this.$emit('closeIconPicker') 
      }
  }
}
</script>
