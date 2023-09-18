<template>
  <i class="el-icon" :class="value" @click="selectIcon"></i>
</template>

<script>
export default{
  name: 'icon',
  props: ['value'],
  methods: {
    selectIcon(){
      console.log("uhuy")
      // this.$store.commit('iconPicker', true)
      const params = {
        title: 'Pilih Ikon',
        selected: this.value,
        onSelected: (icon) => {
          this.$emit('input', icon)
        }
      }
      this.$iconPicker.show(params)
    },
  }
}
</script>