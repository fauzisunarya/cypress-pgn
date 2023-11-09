<template>
  <draggable
    v-model="list"
    v-bind="dragOptions"
    class="sortable"
    handle=".sortable-handle"
    :class="{'is-dragging': drag}"
    @start="drag = true"
    @end="drag = false">
    <slot></slot>
  </draggable>
</template>

<script>
import draggable from 'vuedraggable'
export default {
  name: 'sortable',
  components: {draggable},
  props: ['value'],
  data(){
    return {
      drag: false
    }
  },
  computed: {
    dragOptions() {
      return {
        animation: 200,
        ghostClass: "item-ghost"
      };
    },
    list: {
      get(){
        return this.value
      },
      set(value) {
        this.$emit('input', value)
        this.$store.commit('dirty', true)
      }
    }
  }
}
</script>

<style lang="less">
  .item-ghost{
    background-color: white;
  }
</style>