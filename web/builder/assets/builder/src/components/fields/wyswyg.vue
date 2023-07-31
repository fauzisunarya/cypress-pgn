<template>
<div class="icl-ed-field">
  <label class="form-label">{{data.label}}</label>
  <div class="wyswyg-control">
    <quill-editor
      ref="myQuillEditor"
      v-model="data.value"
      :options="editorOption"
      >
      <div :id="`toolbar-${editorID}`" slot="toolbar">
        <div class="basic-formatting toolbar-row">
          <select class="ql-header" v-if="!data.heading">
            <option value=1>Judul 1</option>
            <!-- Note a missing, thus falsy value, is used to reset to default -->
            <option value=2>Judul 2</option>
            <option value=3>Judul 3</option>
            <option value=4>Judul 4</option>
            <option value=5>Judul 5</option>
            <option value=6>Judul 6</option>
            <option selected>Paragraf</option>
          </select>
          <!-- Add a bold button -->
          <button class="ql-bold">Bold</button>
          <button class="ql-italic">Italic</button>
          <button class="ql-strike">Strike</button>
          <button class="ql-underline">Underline</button>
          
          <button class="ql-list" value="bullet" v-if="!data.heading">bullet</button>
          <button class="ql-list" value="ordered" v-if="!data.heading">ordered</button>

          <button class="ql-clean">clean</button>

          <button class="more-formatting-btn" @click="customButtonClick"><i class="mdi mdi-more_vert"></i></button>
          
        </div>
        <div class="more-formatting toolbar-row" style="display: none">
          <select class="ql-size" v-if="!data.heading">
            <option selected="selected">Normal</option>
            <option value="small">Kecil</option>
            <option value="large">Sedang</option>
            <option value="huge">Besar</option>
          </select>

          <select class="ql-align" v-if="!data.heading"></select>

          <!-- Add subscript and superscript buttons -->
          <button class="ql-script" value="sub"></button>
          <button class="ql-script" value="super"></button>
          <select class="ql-color">
            <option selected="selected"></option>
            <option value="#e60000"></option>
            <option value="#ff9900"></option>
            <option value="#ffff00"></option>
            <option value="#008a00"></option>
            <option value="#0066cc"></option>
            <option value="#9933ff"></option>
            <option value="#ffffff"></option>
            <option value="#facccc"></option>
            <option value="#ffebcc"></option>
            <option value="#ffffcc"></option>
            <option value="#cce8cc"></option>
            <option value="#cce0f5"></option>
            <option value="#ebd6ff"></option>
            <option value="#bbbbbb"></option>
            <option value="#f06666"></option>
            <option value="#ffc266"></option>
            <option value="#ffff66"></option>
            <option value="#66b966"></option>
            <option value="#66a3e0"></option>
            <option value="#c285ff"></option>
            <option value="#888888"></option>
            <option value="#a10000"></option>
            <option value="#b26b00"></option>
            <option value="#b2b200"></option>
            <option value="#006100"></option>
            <option value="#0047b2"></option>
            <option value="#6b24b2"></option>
            <option value="#444444"></option>
            <option value="#5c0000"></option>
            <option value="#663d00"></option>
            <option value="#666600"></option>
            <option value="#003700"></option>
            <option value="#002966"></option>
            <option value="#3d1466"></option>
          </select>
          <select class="ql-background">
            <option value="#000000"></option>
            <option value="#e60000"></option>
            <option value="#ff9900"></option>
            <option value="#ffff00"></option>
            <option value="#008a00"></option>
            <option value="#0066cc"></option>
            <option value="#9933ff"></option>
            <option selected="selected"></option>
            <option value="#facccc"></option>
            <option value="#ffebcc"></option>
            <option value="#ffffcc"></option>
            <option value="#cce8cc"></option>
            <option value="#cce0f5"></option>
            <option value="#ebd6ff"></option>
            <option value="#bbbbbb"></option>
            <option value="#f06666"></option>
            <option value="#ffc266"></option>
            <option value="#ffff66"></option>
            <option value="#66b966"></option>
            <option value="#66a3e0"></option>
            <option value="#c285ff"></option>
            <option value="#888888"></option>
            <option value="#a10000"></option>
            <option value="#b26b00"></option>
            <option value="#b2b200"></option>
            <option value="#006100"></option>
            <option value="#0047b2"></option>
            <option value="#6b24b2"></option>
            <option value="#444444"></option>
            <option value="#5c0000"></option>
            <option value="#663d00"></option>
            <option value="#666600"></option>
            <option value="#003700"></option>
            <option value="#002966"></option>
            <option value="#3d1466"></option>
          </select>
          <button class="ql-link">Link</button>
          <!-- You can also add your own -->
        </div>
      </div>

    </quill-editor>
  </div>
</div>
</template>

<script>
import 'quill/dist/quill.snow.css'
export default {
  name: "field-wyswyg",
  props: {
    data: Object,
    theme: String,
    heading: {
      type: Boolean,
      default: false,
    },
    inPreview: {
      type: Boolean,
      required: false
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
  computed: {
    editorID(){
      return new Date().getTime();
    },
    editorOption(){
      return {
        // some quill options
        theme: 'snow',
        modules: {
          toolbar: `#toolbar-${this.editorID}`,
        }
      };
    },
  },
  methods: {
    // onEditorChange({ html }) {
    //   this.data.value = html
    // },
    customButtonClick() {
      const moreFormatting = this.$refs.myQuillEditor.$el.querySelector('.more-formatting')
      if ( moreFormatting.style.display == "block" )
        moreFormatting.style.display = "none";
      else
        moreFormatting.style.display = "block";
    },
    onEditorBlur(quill) {
      // console.log('editor blur!', quill)
      quill.enable(false);
    },
    onEditorFocus(quill) {
      // console.log('editor focus!', quill)
      quill.enable(true);
    },
  },
}

</script>


<style lang="less">
.wyswyg-control{
    position: relative;
    border: 1px solid #e3e3e3;
    border-radius: 3px;
    background: white;

    .ql-toolbar.ql-snow{
      padding: 0;

      .toolbar-row{
        padding: 5px;
        &:not(:last-child){
          border-bottom: 1px solid #e3e3e3;
        }
      }
    }

    .more-formatting-btn{
      display: inline-block;
      white-space: nowrap;
      width: auto!important;
      float: right;
      margin-left: auto;
      padding: 0!important;
    }
    .toolbar-row:after{
      content:" ";
      clear: both;
      display: block;
    }
}
.editor__content{
  background-color: white;
  textarea{
      outline: none;
      padding: 15px;
  }
}
.menubar{
  display: flex;
  border-bottom: 1px solid #e3e3e3;

  button{
    font-weight: 700;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px 3px;
    background-color: white;
    flex: 1;
    border: none;

  }

}
.ql-editor{
  font-size: 14px;
  font-family: inherit;

  del{
    text-decoration: line-through;
  }
}
.basic-formatting{
  display: flex;
}
</style>