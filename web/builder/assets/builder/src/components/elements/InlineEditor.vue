<template>
  <div
    class="icl-inline-editor"
    contenteditable="true"
    v-html="value"
    @blur="editorChange"
    @paste="onPaste">
    <!-- <quill-editor
      ref="inlineQuillEditor"
      :content="value"
      :options="editorOption"
      @change="onChange">
    </quill-editor> -->
  </div>
</template>

<script>
export default{
  name: 'c-button',
  props: {
    value: [String, Number],
  },
  computed: {
    editorOption(){
      return {
        theme: 'bubble',
        modules: {
          toolbar: [],
        }
      };
    },
  },
  methods: {
    onChange({ html }) {
      return this.$emit('input', html )
    },
    editorChange(event) {
      return this.$emit('input', event.target.innerHTML)
    },
    onPaste(event){
      event.preventDefault();
      var frame = document.getElementById('icl-editor__iframe').contentDocument;
      var text = event.clipboardData.getData("text/plain");
      frame.execCommand("insertHTML", false, text );
    }
  }
}
</script>