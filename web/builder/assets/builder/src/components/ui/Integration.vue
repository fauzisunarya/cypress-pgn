<template>
<div class= "icl-editor__integration icl-editor__tab">
  <div class="icl-editor__integration-content">
    <h3 class="panel-title"><i class="mdi mdi-dashboard"></i> Integrasi</h3>
    <p>Style & Script Kustom untuk kebutuhan website, seperti analytic code dll.</p>

    <div @click="activeIntegrationOption = 'meta-tag' " class="integration-option">Meta Tag</div>
    <div @click="activeIntegrationOption = 'style-script' " class="integration-option">Script & Style Tambahan / Kustom</div>
    <div @click="activeIntegrationOption = 'google-analytics' " class="integration-option">Google Analytics</div>
    <div @click="activeIntegrationOption = 'google-tagmanager' " class="integration-option">Google Tag Manager</div>
    <div @click="activeIntegrationOption = 'google-searchconsole' " class="integration-option">Google Search Console</div>
    <div @click="activeIntegrationOption = 'facebook-pixel' " class="integration-option">Facebook Pixel</div>
    <div @click="activeIntegrationOption = 'whatsapp-button' " class="integration-option">Tombol Whatsapp</div>
    <div @click="activeIntegrationOption = 'contact-form' " class="integration-option">Formulir</div>
    
  </div>

  <transition name="pull-left">
    <div class="icl-editor__integration-options" v-if="activeIntegrationOption">
      <div v-if="activeIntegrationOption == 'meta-tag' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Meta Tag</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Custom Meta Tag</strong></label>
          <codemirror
            v-model="customMeta" 
            :options="{
              tabSize: 2,
              mode: 'text/html',
              lineNumbers: true,
              line: true
            }">
          </codemirror>
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'style-script' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Script & Style tambahan</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Custom Style</strong></label>
          <codemirror
            v-model="customStyle" 
            :options="{
              tabSize: 2,
              mode: 'text/css',
              lineNumbers: true,
              line: true
            }">
          </codemirror>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Custom Script</strong></label>
          <p><em>Custom Javascript hanya akan dijalankan di situs live. Silahkan kunjungi situs untuk mengujinya. Anda tidak perlu menambahkan tag <strong>script</strong></em></p>
          <codemirror
            v-model="customScript" 
            :options="{
              tabSize: 2,
              mode: 'text/javascript',
              lineNumbers: true,
              line: true
            }">
          </codemirror>
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'google-analytics' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Google Analitycs</h2>
        </div>

        <div class="form-group">
          <label class="form-label"><strong>Analytic ID</strong></label>
          <input class="form-control" v-model="google_analytic" placeholder="UA-151XXXXXX-1">
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'google-tagmanager' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Google Tag Manager</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Tag Manager ID</strong></label>
          <input class="form-control" v-model="google_tagmanager" placeholder="GTM-XXXXXXX">
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'google-searchconsole' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Google Search Console</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Verification String</strong></label>
          <input class="form-control" v-model="google_searchconsole" placeholder="XXXXXXX">
          <small>Cukup input verivication string: &lt;meta name="google-site-verification" content="<strong>your verification string</strong>"&gt;</small>
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'facebook-pixel' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Facebook Pixel</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Facebook Pixel ID</strong></label>
          <input class="form-control" v-model="facebook_pixel" placeholder="XXXXXXXXXXX">
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'whatsapp-button' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>WhatsApp Button</h2>
        </div>
        <div class="form-group">
          <label class="form-label"><strong>Nomor Whatsapp-mu</strong></label>
          <input class="form-control" v-model="whatsapp_button" placeholder="+628XXXXXXX">
        </div>
      </div>

      <div v-if="activeIntegrationOption == 'contact-form' " class="icl-editor__integration-options-settings">
        <div class="integration-options-header">
          <button @click="activeIntegrationOption = null"><i class="mdi mdi-arrow_back"></i></button>
          <h2>Formulir</h2>
        </div>

        <div class="page-quota">
          <div class="d-flex justify-content-between mb-2">
            <span>Kuota Formulirmu saat ini</span>
            <span>{{forms.length}} dari {{this.quota}}</span>
          </div>
          <div class="page-quota-progress">
            <div class="page-quota-progress-bar" :style="{width: `${quotaProgress}%` }"></div>
          </div>
        </div>

        <ul class="icl-form-list" v-if="forms.length">
          <li v-for="form in forms">
            <div class="form-title">{{form.form_name}}</div>
            <div class="form-action">
              <button @click="updateForm(form.form_id)"><i class="mdi mdi-edit"></i></button>
              <button @click="deleteForm(form.form_id)">
                <span class="spinner-grow spinner-grow-sm" role="status" v-if="deleting"></span> 
                <i v-else class="material-icons">delete</i>
              </button>
            </div>
          </li>
        </ul>

        <div class="icl-form-empty" v-else>
          <i class="mdi mdi-assignment"></i>
          <p>Anda belum memiliki form, buat sekarang,</p>

          <button @click="createForm" class="btn btn-primary">Tambah formulir</button>
        </div>
        
        <div class="text-right">
          <button v-if="forms.length < quota && forms.length !== 0" @click="createForm" class="btn btn-outline-primary is-fullwidth">Tambah formulir</button>
        </div>

        
      </div>
    </div>
  </transition>

</div>
</template>

<script>
import qs from 'qs'
import Axios from 'axios'
import Color from '../fields/Color'
import Typography from '../fields/Typography'
import Slider from '../fields/Slider'
import { codemirror } from 'vue-codemirror'
import isEmpty from 'lodash/isEmpty'
import draggable from 'vuedraggable'

// require styles
import 'codemirror/mode/javascript/javascript.js'
import 'codemirror/mode/css/css.js'

export default {
  name : "Integration",
  components: {
    "field-color": Color,
    "field-typography": Typography,
    "field-slider": Slider,
    codemirror,
    draggable
  },
  data() {
    return {
      activeIntegrationOption: null,
      schema: null,
      activeField: null,
      deleting: false
    }
  },
  computed: {
    forms(){
      return this.$store.state.project.forms.filter(form=>{
        // default form for telkom cms, the id is the string text ; the id of custom form is integer
        // show only custom form
        return form.form_id.search(/^\d+$/) !== -1;
      });
    },
    quota(){
      return this.$store.state.project.quota
    },
    quotaProgress(){
      return this.forms.length/this.quota*100
    },
    theme(){
      return this.$store.state.project.style
    },
    customMeta:{
      get(){
        return this.$store.state.project.meta.custom_meta_tag
      },
      set( value ) {
        this.$store.commit('UPDATE_SITE_META',{
          field: "custom_meta_tag",
          value
        })
      }
    },
    customStyle:{
      get(){
        return this.$store.state.project.meta.custom_style
      },
      set( value ) {
        this.$store.commit('UPDATE_SITE_META',{
          field: "custom_style",
          value
        })
      }
    },
    customScript:{
      get(){
        return this.$store.state.project.meta.custom_script
      },
      set( value ) {
        this.$store.commit('UPDATE_SITE_META',{
          field: "custom_script",
          value
        })
      }
    },
    google_analytic: {
      get() {
        return this.$store.state.project.meta.google_analytics
      },
      set( value ) {
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "google_analytics",
          value
        } )
      }
    },
    google_tagmanager: {
      get() {
        return this.$store.state.project.meta.google_tagmanager
      },
      set( value ) {
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "google_tagmanager",
          value
        } )
      }
    },
    google_searchconsole: {
      get() {
        return this.$store.state.project.meta.google_searchconsole
      },
      set( value ) {
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "google_searchconsole",
          value
        } )
      }
    },
    facebook_pixel: {
      get() {
        return this.$store.state.project.meta.facebook_pixel
      },
      set( value ) {
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "facebook_pixel",
          value
        } )
      }
    },
    whatsapp_button: {
      get() {
        return this.$store.state.project.meta.whatsapp_button
      },
      set( value ) {
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "whatsapp_button",
          value
        } )
      }
    },
    

  },
  watch: {
    schema: {
      handler(after, before){
        this.$store.commit( 'UPDATE_SITE_META', {
          field: "contact_form",
          value: JSON.stringify( after )
        } )
      },
      deep: true
    }
  },

  methods: {
    updateForm(form_id){
      console.log(form_id);
      this.$FormBuilder.show(form_id);
    },
    deleteForm(id) {
      if ( this.deleting ) return

      this.$swal({
        title: 'Yakin ingin menghapus  formulir?',
        text: "Formulir yang telah dihapus tidak akan bisa diakses lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        if ( result.value ) {
          this.deleting = true
          console.log(id);
          Axios({
            url             : this.$root.config.api+'/custom-form/' + id,
            method          : 'DELETE',
            // withCredentials : true,
            headers         : { 'content-type': 'application/json' },

          }).then(({data})=>{
            if ( status )
              console.log(data);
              this.deleting = false
              return this.$store.commit('REMOVE_FORM', id)
            return false

          }).catch(error=>{
            console.log(error);
            this.deleting = false
          })
        }
      })

    },
    createForm(){
      this.$FormBuilder.show(null)
      // return console.log( this.$store.get('form') );

      // Axios({
      //   url: 'http://kepak.test/api/project/save_custom_form',
      //   method: 'POST',
      //   withCredentials : true,
      //   headers         : { 'content-type': 'application/json' },
      //   data: {
      //     form_name: 'test',
      //     form_scheme: 'Test'
      //   }
      // }).then(response=>{
      //   console.log(response)
      // }).catch(error=>console.log(error))
    },
    onSelectTemplate(value){
      console.log( value )
    },
    isEmpty(exp){
      return isEmpty(exp)
    },
    deleteField(index){
      console.log(this.schema);
      this.schema = [
        ...this.schema.slice(0, index),
        ...this.schema.slice(index+1),
      ]
      console.log(this.schema);
    },
    addField() {
      this.schema.push({
        label: 'Input Label',
        field: 'input',
        type: 'text',
        placeholder: 'Input Placeholder',
        required: false,
        options: [],
        size: 'full'
      })
    },
    removeOption( listIndex, optionIndex ){
      this.schema[listIndex].options = [
        ...this.schema[listIndex].options.slice(0, optionIndex),
        ...this.schema[listIndex].options.slice(optionIndex+1),
      ]
    },
    addOption(index) {
      this.schema[index].options.push( {value: "Option"} )
    }
  }
}
</script>

<style lang="less">
/* BASICS */

.CodeMirror {
  /* Set height, width, borders, and global font properties here */
  font-family: monospace;
  height: 300px;
  color: black;
  direction: ltr;
}

/* PADDING */

.CodeMirror-lines {
  padding: 4px 0; /* Vertical padding around content */
}
.CodeMirror pre.CodeMirror-line,
.CodeMirror pre.CodeMirror-line-like {
  padding: 0 4px; /* Horizontal padding of content */
}

.CodeMirror-scrollbar-filler, .CodeMirror-gutter-filler {
  background-color: white; /* The little square between H and V scrollbars */
}

/* GUTTER */

.CodeMirror-gutters {
  border-right: 1px solid #ddd;
  background-color: #f7f7f7;
  white-space: nowrap;
}
.CodeMirror-linenumbers {}
.CodeMirror-linenumber {
  padding: 0 3px 0 5px;
  min-width: 20px;
  text-align: right;
  color: #999;
  white-space: nowrap;
}

.CodeMirror-guttermarker { color: black; }
.CodeMirror-guttermarker-subtle { color: #999; }

/* CURSOR */

.CodeMirror-cursor {
  border-left: 1px solid black;
  border-right: none;
  width: 0;
}
/* Shown when moving in bi-directional text */
.CodeMirror div.CodeMirror-secondarycursor {
  border-left: 1px solid silver;
}
.cm-fat-cursor .CodeMirror-cursor {
  width: auto;
  border: 0 !important;
  background: #7e7;
}
.cm-fat-cursor div.CodeMirror-cursors {
  z-index: 1;
}
.cm-fat-cursor-mark {
  background-color: rgba(20, 255, 20, 0.5);
  -webkit-animation: blink 1.06s steps(1) infinite;
  -moz-animation: blink 1.06s steps(1) infinite;
  animation: blink 1.06s steps(1) infinite;
}
.cm-animate-fat-cursor {
  width: auto;
  border: 0;
  -webkit-animation: blink 1.06s steps(1) infinite;
  -moz-animation: blink 1.06s steps(1) infinite;
  animation: blink 1.06s steps(1) infinite;
  background-color: #7e7;
}
@-moz-keyframes blink {
  0% {}
  50% { background-color: transparent; }
  100% {}
}
@-webkit-keyframes blink {
  0% {}
  50% { background-color: transparent; }
  100% {}
}
@keyframes blink {
  0% {}
  50% { background-color: transparent; }
  100% {}
}

/* Can style cursor different in overwrite (non-insert) mode */
.CodeMirror-overwrite .CodeMirror-cursor {}

.cm-tab { display: inline-block; text-decoration: inherit; }

.CodeMirror-rulers {
  position: absolute;
  left: 0; right: 0; top: -50px; bottom: 0;
  overflow: hidden;
}
.CodeMirror-ruler {
  border-left: 1px solid #ccc;
  top: 0; bottom: 0;
  position: absolute;
}

/* DEFAULT THEME */

.cm-s-default .cm-header {color: blue;}
.cm-s-default .cm-quote {color: #090;}
.cm-negative {color: #d44;}
.cm-positive {color: #292;}
.cm-header, .cm-strong {font-weight: bold;}
.cm-em {font-style: italic;}
.cm-link {text-decoration: underline;}
.cm-strikethrough {text-decoration: line-through;}

.cm-s-default .cm-keyword {color: #708;}
.cm-s-default .cm-atom {color: #219;}
.cm-s-default .cm-number {color: #164;}
.cm-s-default .cm-def {color: #00f;}
.cm-s-default .cm-variable,
.cm-s-default .cm-punctuation,
.cm-s-default .cm-property,
.cm-s-default .cm-operator {}
.cm-s-default .cm-variable-2 {color: #05a;}
.cm-s-default .cm-variable-3, .cm-s-default .cm-type {color: #085;}
.cm-s-default .cm-comment {color: #a50;}
.cm-s-default .cm-string {color: #a11;}
.cm-s-default .cm-string-2 {color: #f50;}
.cm-s-default .cm-meta {color: #555;}
.cm-s-default .cm-qualifier {color: #555;}
.cm-s-default .cm-builtin {color: #30a;}
.cm-s-default .cm-bracket {color: #997;}
.cm-s-default .cm-tag {color: #170;}
.cm-s-default .cm-attribute {color: #00c;}
.cm-s-default .cm-hr {color: #999;}
.cm-s-default .cm-link {color: #00c;}

.cm-s-default .cm-error {color: #f00;}
.cm-invalidchar {color: #f00;}

.CodeMirror-composing { border-bottom: 2px solid; }

/* Default styles for common addons */

div.CodeMirror span.CodeMirror-matchingbracket {color: #0b0;}
div.CodeMirror span.CodeMirror-nonmatchingbracket {color: #a22;}
.CodeMirror-matchingtag { background: rgba(255, 150, 0, .3); }
.CodeMirror-activeline-background {background: #e8f2ff;}

/* STOP */

/* The rest of this file contains styles related to the mechanics of
   the editor. You probably shouldn't touch them. */

.CodeMirror {
  position: relative;
  overflow: hidden;
  background: white;
}

.CodeMirror-scroll {
  overflow: scroll !important; /* Things will break if this is overridden */
  /* 30px is the magic margin used to hide the element's real scrollbars */
  /* See overflow: hidden in .CodeMirror */
  margin-bottom: -30px; margin-right: -30px;
  padding-bottom: 30px;
  height: 100%;
  outline: none; /* Prevent dragging from highlighting the element */
  position: relative;
}
.CodeMirror-sizer {
  position: relative;
  border-right: 30px solid transparent;
}

/* The fake, visible scrollbars. Used to force redraw during scrolling
   before actual scrolling happens, thus preventing shaking and
   flickering artifacts. */
.CodeMirror-vscrollbar, .CodeMirror-hscrollbar, .CodeMirror-scrollbar-filler, .CodeMirror-gutter-filler {
  position: absolute;
  z-index: 6;
  display: none;
}
.CodeMirror-vscrollbar {
  right: 0; top: 0;
  overflow-x: hidden;
  overflow-y: scroll;
}
.CodeMirror-hscrollbar {
  bottom: 0; left: 0;
  overflow-y: hidden;
  overflow-x: scroll;
}
.CodeMirror-scrollbar-filler {
  right: 0; bottom: 0;
}
.CodeMirror-gutter-filler {
  left: 0; bottom: 0;
}

.CodeMirror-gutters {
  position: absolute; left: 0; top: 0;
  min-height: 100%;
  z-index: 3;
}
.CodeMirror-gutter {
  white-space: normal;
  height: 100%;
  display: inline-block;
  vertical-align: top;
  margin-bottom: -30px;
}
.CodeMirror-gutter-wrapper {
  position: absolute;
  z-index: 4;
  background: none !important;
  border: none !important;
}
.CodeMirror-gutter-background {
  position: absolute;
  top: 0; bottom: 0;
  z-index: 4;
}
.CodeMirror-gutter-elt {
  position: absolute;
  cursor: default;
  z-index: 4;
}
.CodeMirror-gutter-wrapper ::selection { background-color: transparent }
.CodeMirror-gutter-wrapper ::-moz-selection { background-color: transparent }

.CodeMirror-lines {
  cursor: text;
  min-height: 1px; /* prevents collapsing before first draw */
}
.CodeMirror pre.CodeMirror-line,
.CodeMirror pre.CodeMirror-line-like {
  /* Reset some styles that the rest of the page might have set */
  -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0;
  border-width: 0;
  background: transparent;
  font-family: inherit;
  font-size: inherit;
  margin: 0;
  white-space: pre;
  word-wrap: normal;
  line-height: inherit;
  color: inherit;
  z-index: 2;
  position: relative;
  overflow: visible;
  -webkit-tap-highlight-color: transparent;
  -webkit-font-variant-ligatures: contextual;
  font-variant-ligatures: contextual;
}
.CodeMirror-wrap pre.CodeMirror-line,
.CodeMirror-wrap pre.CodeMirror-line-like {
  word-wrap: break-word;
  white-space: pre-wrap;
  word-break: normal;
}

.CodeMirror-linebackground {
  position: absolute;
  left: 0; right: 0; top: 0; bottom: 0;
  z-index: 0;
}

.CodeMirror-linewidget {
  position: relative;
  z-index: 2;
  padding: 0.1px; /* Force widget margins to stay inside of the container */
}

.CodeMirror-widget {}

.CodeMirror-rtl pre { direction: rtl; }

.CodeMirror-code {
  outline: none;
}

/* Force content-box sizing for the elements where we expect it */
.CodeMirror-scroll,
.CodeMirror-sizer,
.CodeMirror-gutter,
.CodeMirror-gutters,
.CodeMirror-linenumber {
  -moz-box-sizing: content-box;
  box-sizing: content-box;
}

.CodeMirror-measure {
  position: absolute;
  width: 100%;
  height: 0;
  overflow: hidden;
  visibility: hidden;
}

.CodeMirror-cursor {
  position: absolute;
  pointer-events: none;
}
.CodeMirror-measure pre { position: static; }

div.CodeMirror-cursors {
  visibility: hidden;
  position: relative;
  z-index: 3;
}
div.CodeMirror-dragcursors {
  visibility: visible;
}

.CodeMirror-focused div.CodeMirror-cursors {
  visibility: visible;
}

.CodeMirror-selected { background: #d9d9d9; }
.CodeMirror-focused .CodeMirror-selected { background: #d7d4f0; }
.CodeMirror-crosshair { cursor: crosshair; }
.CodeMirror-line::selection, .CodeMirror-line > span::selection, .CodeMirror-line > span > span::selection { background: #d7d4f0; }
.CodeMirror-line::-moz-selection, .CodeMirror-line > span::-moz-selection, .CodeMirror-line > span > span::-moz-selection { background: #d7d4f0; }

.cm-searching {
  background-color: #ffa;
  background-color: rgba(255, 255, 0, .4);
}

/* Used to force a border model for a node */
.cm-force-border { padding-right: .1px; }

@media print {
  /* Hide the cursor when printing */
  .CodeMirror div.CodeMirror-cursors {
    visibility: hidden;
  }
}

/* See issue #2901 */
.cm-tab-wrap-hack:after { content: ''; }

/* Help users use markselection to safely style text background */
span.CodeMirror-selectedtext { background: none; }

.icl-editor__tab.icl-editor__integration{
  padding: 0;

  .icl-editor__integration-content,
  .icl-editor__integration-options{
    display: flex;
    flex-direction: column;
    position: absolute;
    width: 100%;
    top: 0;
    bottom: 0;
    left: 0;
    overflow: auto;
    padding: 15px;

  }
  .vue-codemirror-wrap{
    border: 1px solid #e3e3e3;
    .CodeMirror{
      height: 200px;
    }
  }


}
// .theme-section-title{
//   font-size: 18px;
//   margin-bottom: 15px;
//   margin: 30px -15px 15px;
//   padding: 10px 20px;
//   background-color: white;
// }
.icl-editor{
  .font-picker{
    width: 100%;
    box-shadow: none;

    .dropdown-button{
      background: white;
      border: 1px solid #e3e3e3;
      border-radius: 3px;
      padding: 10px 15px;
      height: auto;

      &:hover, &.expanded{
        background: #fbfbfb;
      }
    }
    .expanded{
      background: #fbfbfb;
      button{
        font-size: 20px;
      }
    }
  }
}

.integration-option{
  padding: 15px;
  font-weight: 400;
  background-color: white;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 0 0 1px #e3e3e3;
  &:not(:last-child){
    border-bottom: 1px solid #e3e3e3
  }
  &:hover{
    background-color: #fbfbfb;
  }
}

.icl-editor__integration-options{
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  transform: translateX(0);
  transition: .3s ease;
  background: #f8f9fa;
  left: 0;
  padding: 15px;
}
.integration-options-header{
  display: flex;
  align-items: center;
  margin-bottom: 30px;
  button{
    border: none;
    background: transparent;
  }
  h2{
    font-size: 18px;
    padding-left: 15px;
  }
}

.icl-form-list{
  list-style: none;
  margin: 0 0 15px;
  padding: 0;

  li{
    display: flex;
    align-items: center;
    background-color: white;
    box-shadow: 0 0 0 1px rgba(0,0,0,.1);
  }
  .form-title{
    flex: 1;
    padding: 10px 15px;
  }
  .form-action{
    display: flex;
    button{
      border: none;
      background-color: none;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #777;

      i{
        line-height: 1;
        font-size: 18px;
      }
    }
  }
}

.form-fields{
  margin-bottom: 15px;
}
.icl-form-empty{
  border: 2px dashed #e3e3e3;
  padding: 30px;
  border-radius: 10px;
  text-align: center;

  i{
    font-size: 48px;
    color: rgba(0,0,0,.3);
    text-shadow: 0 1px 1px white;
  }
}
</style>