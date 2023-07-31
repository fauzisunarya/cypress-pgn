<template>
  <div id="app">
    <Builder/>
    <notifications
      group="builder"
      position="bottom right"
      classes="icl-notification"
      />
    <icon-picker-modal/>
    <media-picker-modal/>
    <block-modal/>
    <template-modal/>
    <link-builder/>
    <form-builder/>
  </div>
</template>

<script>
import Builder from './views/Builder.vue'
export default{
  name: 'app',
  components: {
    Builder
  },
  mounted(){
    window.addEventListener("dragover",function(e){
      e = e || event;
      e.preventDefault();
    },false);
    window.addEventListener("drop",function(e){
      e = e || event;
      e.preventDefault();
    },false);
  }
}
</script>

<style lang="less">
body{
  margin: 0;
}
#app {
  // font-family: Roboto, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
.icl-notification {
  padding: 15px 15px 15px 60px;
  margin: 0 5px 5px;

  font-size: 14px;
 
  color: #ffffff;
  background: darken(#44A4FC,15%);
  border-left: 5px solid darken(#187FE7,20%);
  border-radius: 4px;
  position: relative;

  &:before{
    content:'check_circle';
    font-family: 'Material Icons';
    position: absolute;
    left: 20px;
    font-size: 32px;
    top: 15px;
    line-height: 1;
  }

  .notification-title {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 14px;
  }
  
  &.warn {
    background: darken(#ffb648,15%);
    border-left-color: darken(#f48a06,20%);
  }
 
  &.error {
    background: darken(#E54D42,15%);
    border-left-color: darken(#B82E24,20%);
  }
 
  &.success {
    background: darken(#68CD86,15%);
    border-left-color: darken(#42A85F,20%);
  }
}
</style>
