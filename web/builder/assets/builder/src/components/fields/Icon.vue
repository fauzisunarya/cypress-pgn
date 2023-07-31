<template>
  <div class="icl-ed-field icl-ed-field--icon" :class="{'icl-ed-field--horizontal':data.horizontal}">
    <div class="icon-field">
      <label class="form-label">{{data.label}}</label>
      <div>
        <span class="icon-picker-btn" @click="selectIcon">
          <i v-if="data.value" :class="data.value"></i>
          <i v-else class="material-icons">add</i>
        </span>
        <button v-if="data.value" class="icon-picker-clear" @click="clearInput"><i class="mdi mdi-close"></i></button>
      </div>
    </div>
    <!-- <IconPicker 
      :active="active"
      :value="data.value"
      @iconPicked="iconSelected"
      @closeIconPicker="closeIconPicker"
      /> -->
  </div>
</template>

<style lang='less'>

.icl-ed-field--icon{
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  .icon-field{
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
  .icon-picker-btn{
    cursor: pointer;
    font-size: 24px;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 1px dashed rgba(0,0,0,.5);
    border-radius: 5px;
    background-color: #fbfbfb;
  }
  .icon-picker-clear{
    width: 24px;
    height: 24px;
    border-radius: 50%;
    position: absolute;
    top: -5px;
    right: -5px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;

    .material-icons{
      font-size: 16px;
      line-height: 1;
    }
  }
}
</style>

<script>
// import IconPicker from '@/components/ui/IconPicker'
export default {
  name: 'field-icon',
  // components: { IconPicker },
  props: {
    data: Object
  },
  data(){
    return {
      active: false,
    }
  },
  computed:{
    activeSidebar() {
      return this.$store.state.editorState.activeSidebarTab
    }
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
  methods: {
    selectIcon(){
      this.active = true
      // this.$store.commit('iconPicker', true)
      const params = {
        title: 'Pilih Ikon',
        selected: this.data.value,
        onSelected: (icon) => {
          this.data.value = icon
        }
      }
      this.$iconPicker.show(params)
    },
    clearInput(){
      this.data.value = '',
      this.$forceUpdate()
    }
  }
}
</script>