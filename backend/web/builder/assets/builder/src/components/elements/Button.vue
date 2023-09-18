<template>
  <a
    v-if="data.enable"
    :href="data.url"
    :target="newWindow"
    :class="`button ${data.size} ${data.style} ${data.corner} ${data.is_fullwidth ? 'is-fullwidth' : ''}`"
    :style="{
      '--background': data.background,
      '--foreground': data.color,
      'margin-bottom': '10px',
      'white-space': 'normal',
    }"
  >
    <span class="icon" v-if="data.icon_position == 'left' && data.icon"
      ><i :class="data.icon"></i
    ></span>
    <span>{{ data.label }}</span>
    <span class="icon" v-if="data.icon_position == 'right' && data.icon"
      ><i :class="data.icon"></i
    ></span>
  </a>
</template>

<script>
export default {
  name: "c-button",
  props: {
    data: Object,
  },
  computed: {
    newWindow() {
      return this.data.new_window ? "__blank" : "";
    },
  },
};
</script>
