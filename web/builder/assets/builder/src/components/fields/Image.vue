<template>
<div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
  
  <label class="form-label" :style="data.label === 'Logo' ? 'display: none;' : ''">{{data.label}}</label>
  <div class="input-group">
    <input
      class="form-control" :style="data.label === 'Logo' ? 'display: none;' : ''"
      type="text"
      v-model="data.value" />
    <div class="input-group-append" :style="data.label === 'Logo' ? 'display: none;' : ''" @click="showMediaPicker">
      <span class="input-group-text"><i class="mdi mdi-image mr-2"></i>Media</span>
    </div>
  </div>

</div>
</template>

<script>
export default {
  name: 'field-image',
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
  methods: {
    showMediaPicker(){
      console.log(this.data.value)
      this.$mediaPicker.show({
        title: "Select an image",
        selected: [this.data.value],
        multiple: false,
        onSelected: (value)=>{
          this.data.value = value[0]
        }
      })
    },
  },
  mounted() {
    this.$nextTick(function () {
      this.data.value = this.data.label === "Logo" ? this.$store.state.project.logo : this.data.value;
    })
  }
}
</script>

<style lang="less">
  .media-picker{
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    margin-top: 10px;
    &-option{
      display: flex;
    }
    &-upload{
      flex: 1;
      margin: 0!important;
      &:active{
        background-color: var(--primary);
        color: white;
      }
      input{
        display: none;
      }
      div{
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 15px;
      }
    }
    &-library{
      flex: 1;
      background-color: transparent;
      border: none;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 15px;

      &.is-active{
        background-color: var(--primary);
        color: white;
      }
    }
    .material-icons {
      margin-right: 15px;
    }

    &-grid{
      border-top: 1px solid #e3e3e3;
      display: flex;
      flex-wrap: wrap;
      padding: 5px;
      max-height: 358px;
      overflow: auto;

      div{
        width: 33.333%;
        padding: 5px;

        img{
          width: 100%;
        }
      }
    }
  }
</style>