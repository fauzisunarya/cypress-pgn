<template>
<div class="icl-ed-field" :class="{'icl-ed-field--horizontal':data.horizontal}">
  <label class="form-label">{{data.label}}</label>
  <div class="input-group" v-if="data.input_type && data.input_type == 'textarea'">
    <textarea v-model="computedValue" class="form-control"></textarea>
  </div>

  <div class="code-input" v-if="data.input_type && data.input_type == 'code'">
    <codemirror
      v-model="computedValue"
      ref="codemirror"
      :options="{
        tabSize: 2,
        mode: 'text/html',
        lineNumbers: true,
        line: true
      }"
      >
    </codemirror>
    <div class="form-help">Javascript tidak akan dijalankan saat anda berada di dalam editor. silahkan kunjungi situs.</div>
  </div>

  <div class="input-group" v-if="data.input_type && data.input_type == 'link'">
    <a
      target="_blank"
      rel="noopener noreferrer"
      :href="logoConfigurationLink">
        Konfigurasi Logo
    </a>
  </div>

  <div class="input-group" v-else>
    <div v-if="data.prepend" class="input-group-prepend">
      <div class="input-group-text">{{data.prepend}}</div>
    </div>
    <input
      class="form-control"
      type="text"
      v-model="computedValue" />
    <div v-if="data.append" class="input-group-append">
      <div class="input-group-text">{{data.append}}</div>
    </div>
  </div>
</div>
</template>

<script>
import { codemirror } from 'vue-codemirror'

// require styles
import 'codemirror/mode/javascript/javascript.js'
import 'codemirror/mode/css/css.js'
import 'codemirror/mode/htmlmixed/htmlmixed.js'
export default {
  name: 'field-text',
  components:{
    codemirror
  },
  props: {
    data: Object
  },
  mounted(){
    if ( this.data.input_type && this.data.input_type === "code" ) {
      this.$store.subscribe( ( mutation ) => {
        if ( mutation.type == 'ACTIVE_BLOCK' ) {
          if (this.$refs.codemirror ) this.$refs.codemirror.refresh();
        }
      });
    }
  },
  computed: {
    logoConfigurationLink() {
      return window.location.href.replace(/\/builder.*/i, '') + '/edit/landing-page/' + this.$store.state.project.site_uuid;
    },
    computedValue:{
      get(){
        return this.data.value //.replace(/(<([^>]+)>)/ig,"").replace('&nbsp;','');
      },
      set(value) {
        this.data.value = value
      }
    },
  },
  watch: {
    'data.value' : {
      handler(){
        this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
}
</script>