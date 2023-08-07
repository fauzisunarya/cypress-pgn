<template>
  <div class="sortable-item">
    <div class="sortable-option">
      <span @click.prevent.stop class="sortable-handle"><i class="mdi mdi-drag_handle"></i></span>
      <button @click.prevent.stop="itemDuplicate"><i class="mdi mdi-file_copy"></i></button>
      <button @click.prevent.stop="itemDelete"><i class="mdi mdi-close"></i></button>
    </div>
    <slot></slot>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
export default {
  name: 'sortable-item',
  props: {
    list: Array,
    index: Number
  },
  methods: {
    itemDuplicate(){
      const clone = cloneDeep( this.list[this.index] );
      this.list.splice( this.index, 0, clone );
      this.$store.commit('dirty', true);
    },
    itemDelete(){
      this.list.splice( this.index, 1 );
      this.$store.commit('dirty', true);
    }
  }
}
</script>

<style lang="less">

</style>