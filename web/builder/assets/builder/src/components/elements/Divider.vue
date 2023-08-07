<template>
  <div class="icl-divider">
    <div class="icl-divider__top" :data-invert="data.top.invert">
      <divider-shape :type="data.top.type" :negative="data.top.invert" :color="data.top.color" :width="data.top.width" :height="data.top.height" />
    </div>
    <div class="icl-divider__bottom" :data-invert="data.bottom.invert">
      <divider-shape :type="data.bottom.type" :negative="data.bottom.invert" :color="data.bottom.color" :width="data.bottom.width" :height="data.bottom.height" />
    </div>
  </div>
</template>

<script>

import DividerShape from './DividerShape';

export default {
  name: "Divider",
  components: {
    'divider-shape': DividerShape
  },
  props: {
    data: Object
  },
}
</script>