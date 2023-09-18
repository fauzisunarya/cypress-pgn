<template>
  <transition name="fade">
    <div class="icl-editor__form-builder-modal" v-if="visible">
      <div class="icl-editor__form-builder-modal-window">
        <div class="form-builder__header">
          <button @click="closeForm" class="close-form-builder"><i class="mdi mdi-close"></i></button>
          <h2>Formulir Editor</h2>
          <button @click="saveForm" class="btn btn-primary"><span class="spinner-grow spinner-grow-sm" role="status" v-if="saving"></span> {{!saving ? 'Simpan Form': 'Menyimpan form'}}</button>
        </div>
        <div class="form-builder__editor">
          <div class="form-settings">
            <div class="form-meta">
              <label for="title">Nama Formulir</label>
              <input id="title" v-model="form_name" class="form-control" type="text" placeholder="Nama Form"/>
            </div>
            <div class="form-settings-tab">
              <li @click="activeTab = 'fields' " :class="{'is-active': activeTab == 'fields' }"><i class="mdi mdi-list"></i> Kolom Input</li>
              <li @click="activeTab = 'feedback' " :class="{'is-active': activeTab == 'feedback' }"><i class="mdi mdi-offline_bolt"></i> Umpan Balik</li>
            </div>
            <div class="form-settings-tab-content">
              <div v-if="activeTab == 'fields' " class="form-settings-tab-item form-settings__fields">
                <draggable
                    tag="transition-group"
                    class="form-fields default-fields"
                    v-model="fields"
                    draggable=".contact-form-field"
                    :animation="100"
                    :componentData="componentData"
                    handle=".form-field-head"
                    >
                    <div class="contact-form-field" v-for="(field,index) in fields" :key="`item-${index}`">
                      <div class="form-field-head">
                        <span>{{field.label}}</span>
                        <button @click="activeField = activeField == index ? null : index"><i class="mdi mdi-settings"></i></button>
                        <button class="delete-field" @click="deleteField(index)"><i class="mdi mdi-delete"></i></button>
                      </div>

                      <div class="form-field-setting" v-if="activeField == index">

                        <template>
                          <div class="form-field">
                            <label class="form-label">Nama/ID Input</label>
                            <div class="form-control-body">
                              <input class="form-control" type="text" v-model="field.name">
                            </div>
                          </div>
                          <div class="form-field">
                            <label class="form-label">Label</label>
                            <div class="form-control-body">
                              <input class="form-control" type="text" v-model="field.label">
                            </div>
                          </div>
                        </template>

                        <div class="form-field">
                          <label class="form-label">Jenis Input</label>
                          <div class="form-control-body">
                            <select class="form-control" v-model="field.field" @change="onChangeField(index)">
                              <option value="input">Input</option>
                              <option value="select">Select</option>
                              <option value="textarea">Textarea</option>
                              <option value="checkbox">Checkbox</option>
                              <option value="radio">Radio</option>
                              <option value="file">File</option>
                              <option value="package">Package</option>
                              <!-- limit coordinate with map to only 1 -->
                              <option value="coordinate" v-if=" fields.filter(v => v.field == 'coordinate').length == 0 || field.field == 'coordinate' " >Coordinate</option>
                              <option value="signature" v-if=" fields.filter(v => v.field == 'signature').length == 0 || field.field == 'signature' " >Signature</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-field" v-if="field.field === 'input'">
                          <label class="form-label">Tipe Input</label>
                          <div class="form-control-body">
                            <select class="form-control" v-model="field.type">
                              <option value="text">Text</option>
                              <option value="email">Alamat Email</option>
                              <option value="url">Alamat Website</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-field" v-if="field.field === 'file'">
                          <label class="form-label">Tipe File</label>
                          <div class="form-control-body">
                            <select class="form-control" v-model="field.type">
                              <option value="file">File</option>
                              <option value="ktp" v-if=" fields.filter(v => v.field == 'file' && v.type == 'ktp').length == 0 || field.type == 'ktp' ">KTP</option> 
                              <option value="selfie" v-if=" fields.filter(v => v.field == 'file' && v.type == 'selfie').length == 0 || field.type == 'selfie' ">Foto selfie</option>
                            </select>
                          </div>
                        </div>

                        <!-- placeholder -->
                        <div class="form-field" v-if="['textarea','input','select', 'coordinate', 'package'].includes(field.field)">
                          <label class="form-label">Placeholder</label>
                          <div class="form-control-body">
                            <input class="form-control" type="text" v-model="field.placeholder">
                          </div>
                        </div>
                        <!-- required ? -->
                        <div class="form-field" v-if="['textarea','input','select','coordinate', 'package', 'file'].includes(field.field)">
                          <label class="form-label">Harus diisi?</label>
                          <div class="form-control-body">
                            <div class="field-switch">
                              <label>
                                <input type="checkbox" v-model="field.required">
                                <span class="field-switch-ui"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-field" v-if=" ! ['ktp', 'selfie'].includes(field.type)">
                          <label class="form-label">Ukuran</label>
                          <div class="form-control-body">
                            <select v-model="field.size" class="form-control">
                              <option value="full">1 Kolom</option>
                              <option value="half">1/2 Kolom</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-field form-field-options" v-if="field.field == 'select' || field.field == 'checkbox' || field.field == 'radio' ">
                          <label class="form-label">Opsi</label>
                          <div class="form-control-body">
                            <div class="form-select-option" v-for="(option, idx) in field.options">
                              <input type="text" v-model="option.value">
                              <button @click="removeOption(index, idx)"><i class="mdi mdi-delete"></i></button>
                            </div>

                            <button class="btn btn-primary" @click="addOption(index)">Tambah Opsi</button>
                          </div>
                        </div>

                        <!-- optional add field address for field coordinate -->
                        <div class="form-field" v-if="field.field == 'coordinate'">
                          <label class="form-label">Tampilkan alamat?</label>
                          <div class="form-control-body">
                            <div class="field-switch">
                              <label>
                                <input type="checkbox" v-model="field.otherfield.show">
                                <span class="field-switch-ui"></span>
                              </label>
                            </div>
                          </div>
                        </div>

                        <!-- setting for otherfield -->
                        <template v-if="field.otherfield.show">
                          <template v-if="field.field == 'coordinate'">
                            <div class="form-field">
                              <label class="form-label">Nama/ID alamat</label>
                              <div class="form-control-body">
                                <input class="form-control" type="text" v-model="field.otherfield.name">
                              </div>
                            </div>
                            <div class="form-field">
                              <label class="form-label">Label alamat</label>
                              <div class="form-control-body">
                                <input class="form-control" type="text" v-model="field.otherfield.label">
                              </div>
                            </div>
                            <div class="form-field">
                              <label class="form-label">Placeholder</label>
                              <div class="form-control-body">
                                <input class="form-control" type="text" v-model="field.otherfield.placeholder">
                              </div>
                            </div>
                          </template>
                        </template>

                      </div>
                    </div>

                    <div
                      slot="footer"
                      class="field-submit-button"
                      role="group"
                      aria-label="Submit button"
                      key="footer"
                    >
                      <!-- submit button -->
                      <div class="form-field-head">
                        <span>{{submit.label}}</span>
                        <button @click="submitActive= !submitActive"><i class="mdi mdi-settings"></i></button>
                      </div>

                      <div class="form-field-setting" v-if="submitActive">
                        <div class="form-field">
                          <label class="form-label">Label</label>
                          <div class="form-control-body">
                            <input class="form-control" type="text" v-model="submit.label">
                          </div>
                        </div>

                        <div class="form-field">
                          <label class="form-label">Posisi tombol</label>
                          <div class="form-control-body">
                            <select v-model="submit.position" class="form-control">
                              <option value="left">Kiri</option>
                              <option value="center">Tengah</option>
                              <option value="right">Kanan</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-field">
                          <label class="form-label">Kirim Data Ke</label>
                          <div class="form-control-body">
                            <select v-model="submit.url" class="form-control">
                              <option value="cms">CMS</option>
                              <option value="mydita">MYDITA</option>
                            </select>
                          </div>
                        </div>
                      </div>

                    </div>
                </draggable>

                <div class="fields-empty" v-if="!fields.length">
                  Formulir ini masih kosong, Silahkan tambahkan kolom input minimal 1 kolom
                </div>

                <button @click="addField" class="add-field btn btn-outline-secondary d-block">Tambah Kolom Input</button>
              </div>
              <div v-if="activeTab == 'feedback' " class="form-settings-tab-item form-settings__feedback">
                <div class="form-group">
                  <label>Pesan sukses</label>
                  <quill-editor
                    ref="formBuilderEditor"
                    v-model="actions.message"
                    :options="editorOption"
                    >
                  </quill-editor>
                  <div class="form-text text-muted">Pesan yang akan muncul setelah formulir dikirim</div>
                </div>

                <div class="form-group">
                  <label>Posisi Pesan</label>
                  <select class="form-control" v-model="actions.position">
                    <option value="top">Di atas Form</option>
                    <option value="bottom">Di bawah Form</option>
                    <option value="cover">Pop Up</option>
                  </select>
                </div>
              </div>
            </div>

          </div>
          <div class="form-preview-wrap">
            <div class="form-preview">
              <div class="form">

                <div v-if="actions.position === 'top' && showMessage" class="success-message is-top">
                  <button @click="showMessage = false" class="close-message"><i class="mdi mdi-close"></i></button>
                  <div class="success-message-content" v-html="actions.message"></div>
                </div>

                <ContactForm :data="cfData" :onSubmit="onSubmitFormPreview" />

                <div v-if="actions.position !== 'top' && showMessage" class="success-message" :class="{'is-cover' : actions.position === 'cover', 'is-bottom': actions.position === 'bottom' }">
                  <button @click="showMessage = false" class="close-message"><i class="mdi mdi-close"></i></button>
                  <div class="success-message-content" v-html="actions.message"></div>
                </div>

              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import Modal from './index'
import isEmpty from 'lodash/isEmpty'
import findIndex from 'lodash/findIndex'
import draggable from 'vuedraggable'
import ContactForm from '../ContactForm'
import Axios from 'axios'

import 'quill/dist/quill.snow.css'

// "otherfield" inside each field, to make all field property equal.
// "otherfield" currently used for coordinate field
const defaultFields = [
  {
    name: "nama",
    label: "Nama Lengkap",
    field: "input",
    type: "text",
    placeholder: "Nama lengkap anda",
    required: true,
    options: [],
    size: "full",
    otherfield: {
      name: "",
      label: "Label",
      placeholder: "Text sementara",
      show: false
    }
  },
  {
    name: "email",
    label: "Alamat Email",
    field: "input",
    type: "email",
    placeholder: "Alamat email anda",
    required: true,
    options: [],
    size: "full",
    otherfield: {
      name: "",
      label: "Label",
      placeholder: "Text sementara",
      show: false
    }
  },
  {
    name: "no_telepon",
    label: "Nomor Telepon",
    field: "input",
    type: "text",
    placeholder: "Nomor Telepon",
    required: true,
    options: [],
    size: "full",
    otherfield: {
      name: "",
      label: "Label",
      placeholder: "Text sementara",
      show: false
    }
  },
  {
    name: "pesan",
    label: "Pesan",
    field: "textarea",
    type: "text",
    placeholder: "Masukkan pesan anda disini",
    required: true,
    options: [],
    size: "full",
    otherfield: {
      name: "",
      label: "Label",
      placeholder: "Text sementara",
      show: false
    }
  }
]

export default {
  name: 'form-builder',
  components:{draggable,ContactForm},
  data() {
    return {
      visible    : false,
      activeTab  : 'fields',
      dirty: false,

      form_name: '',
      form_id: null,
      fields: defaultFields,

      submit: {
        label: 'Kirim Pesan',
        position: 'right',
        url: 'cms'
      },
      submitActive: false,
      showMessage: false,

      
      actions: {
        position: 'top',
        message : 'Halo, terima kasih telah menghubungi kami, kami akan mengontak anda kembali',
      },

      activeField: null,
      saving: false,

      componentData:{
        props: {
          type: "transition",
          name: "flip-list"
        }
      }
    }
  },

  computed: {
    cfData(){
      return {
        fields: this.fields,
        submit: this.submit,
        actions: this.actions
      }
    },
    editorOption(){
      return {
        // some quill options
        theme: 'snow',
        modules: {
          toolbar: [

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'align': [] }],
            ['link'],

            ['clean']
          ],
        },
      };
    },
  },

  methods: {
    onSubmitFormPreview(){
      this.showMessage = true;
    },
    customButtonClick() {
      const moreFormatting = this.$refs.formBuilderEditor.$el.querySelector('.more-formatting')
      if ( moreFormatting.style.display == "block" )
        moreFormatting.style.display = "none";
      else
        moreFormatting.style.display = "block";
    },
    show(id) { 
      if ( id ) {
        const index = findIndex( this.$store.state.project.forms, item => item.form_id == id )
        const formData = this.$store.state.project.forms[index];
        const schema   = JSON.parse(formData.form_scheme);
        // return console.log( schema )
        
        this.form_id   = formData.form_id
        this.form_name = formData.form_name
        this.fields    = schema.fields
        this.submit    = schema.submit
        this.actions   = schema.actions
        this.activeField = null
        if(!this.submit.url) { 
          this.submit.url = 'cms'
        }

      } else {
        this.form_id   = null,
        this.form_name = "",
        this.fields    = defaultFields
        this.submit    = {          
          label: 'Kirim Pesan',
          position: 'right',
          url: 'cms'
        },
        this.actions     = {
          position: 'top',
          message : 'Halo, terima kasih telah menghubungi kami, kami akan mengontak anda kembali',
        },
        this.activeField = null
      }
      this.visible = true
    },
    closeForm(){
      this.visible = false
    },
    deleteField(index){
      this.fields = [
        ...this.fields.slice(0, index),
        ...this.fields.slice(index+1),
      ]
    },
    addField() {
      this.fields.push({
        name        : '',
        label       : 'Label',
        field       : 'input',
        type        : 'text',
        placeholder : 'Teks sementara',
        required    : false,
        options     : [],
        size        : 'full',
        otherfield  : {
          name: "",
          label: "Label",
          placeholder: "Text sementara",
          show: false
        }
      })
    },
    onChangeField(index){
      if ( this.fields[index].field === 'input' && ['file','ktp', 'selfie'].includes(this.fields[index].type) ) {
        this.fields[index].type = 'text';
      }
      else if( this.fields[index].field === 'file' && ['text','email','url'].includes(this.fields[index].type) ){
        this.fields[index].type = 'file';
      }
      else if (this.fields[index].field === 'signature'){
        this.fields[index].required = true;
      }
    },
    removeOption( listIndex, optionIndex ){
      this.fields[listIndex].options = [
        ...this.fields[listIndex].options.slice(0, optionIndex),
        ...this.fields[listIndex].options.slice(optionIndex+1),
      ]
    },
    addOption(index) {
      const next = this.fields[index].options.length+1
      this.fields[index].options.push( {value: `Option ${next}`} )
    },
    async saveForm(){
      if ( this.saving ) return
      if ( this.form_name.trim() == "") return alert('Nama form tidak boleh kosong')
      if ( this.fields.length == 0 ) return alert( 'Minimal ada satu kolom input dalam formulir' )

      const emptyName = this.fields.filter( field => field.name.trim() === "" || (field.otherfield.show && field.otherfield.name.trim() === '') );
      const fieldNames = this.fields.map( item => item.name );
      const duplicate = []
      const isDuplicate = fieldNames.some( (item, idx) => {
        if (fieldNames.indexOf(item) !== idx) {
          duplicate.push(item);
          return item
        }
      } )

      if ( emptyName.length ) return alert(`Nama/ID input harus diisi: ${emptyName.map(item=>item.label).join(', ')}`);

      if ( isDuplicate ) return alert( `Nama/ID input tidak boleh sama: ${duplicate.join(', ')}` )


      const schema = {
        fields  : this.fields,
        submit  : this.submit,
        actions : this.actions
      }

      this.saving = true

      const data = {
        form_name: this.form_name,
        form_scheme: JSON.stringify(schema) 
      }

      if ( this.form_id ) {
        data.form_id = this.form_id
      }

      // get drupal token
      let csrf_token = '';
      let config_api = this.$root.config.api; // output : baseurl/api/v1/project
      let baseurl = config_api.split('/api/v1')[0]; 
      await Axios.get(`${baseurl}/session/token`)
        .then( response => {
          csrf_token = response.data;
        } )
      
      Axios({
        url: this.$root.config.api+'/custom-form',
        method: 'POST',
        // withCredentials : true,
        headers: {"X-CSRF-Token": csrf_token, 'Content-Type': 'application/json' },
        data
      }).then(({data})=>{

        if ( !this.form_id ) {
          this.form_id = data.data;

          this.$store.commit('ADD_FORM',{
            form_id: this.form_id,
            form_name: this.form_name,
            form_scheme: JSON.stringify( schema )
          })
        } else {
          const index = findIndex( this.$store.state.project.forms, item => item.form_id == this.form_id );
          this.$store.commit('UPDATE_FORM',{
            index: index,
            value: {
              form_id: this.form_id,
              form_name: this.form_name,
              form_scheme: JSON.stringify( schema )
            }
          })
        }

        this.$store.commit('dirty', true)

        this.saving = false
        this.visible = false
      }).catch(error=>{
        console.log(error)
        this.saving = false
      })

    }
  },
  beforeMount(){
    Modal.event.$on('show', (params)=>{
      this.show(params)
      if ( !isEmpty(params) ) {

        // return console.log( params );
        // 
        const data = this.$store.state.project.forms.filter( i => i.form_id == params);

        this.form_id = data[0].form_id
        this.form_name = data[0].form_name
        const schema = JSON.parse(data[0].form_scheme);

        this.fields  = schema.fields
        this.actions = schema.actions
      }
    })
  },
}
</script>

<style lang="scss">
  .icl-editor__form-builder-modal{
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background-color: rgba(0,0,0,.8);
    // backdrop-filter: blur(2px);
    &-window{
      width: 100%;
      background-color: white;
      box-shadow: 0 10px 30px rgba(0,0,0,.5);
      border-radius: 6px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      overflow: hidden;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 90vh;
      height: calc(100vh - 100px);
      // height: 100%;
      max-width: 1200px;
      margin: 0 auto;
      font-size: 14px;

      display: flex;
      flex-direction: column;

      @media screen and (max-height: 768px){
        height: 100vh;  
      }
    }

    .form-builder__header{
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding: 15px;
      align-items: center;
      border-bottom: 1px solid #e3e3e3;

      h2{
        font-size: 18px;
        margin: 0 auto 0 0;
        padding-left: 15px;
      }
    }
    .form-builder__editor{
      flex: 1;
      display: flex;
    }

    .form-fields{
      margin-bottom: 15px;
      display: block;
    }

    .form-meta{
      padding: 20px 15px;
      background-color: #fbfbfb;
      display: flex;
      align-items: center;

      label{
        margin-bottom: 0;
        padding-right: 15px;
        font-weight: 700;
        font-size: 14px;
      }

      input{
        flex: 1;
      }
    }

    .form-settings {
      width: 400px;
      display: flex;
      flex-direction: column;
      &-tab{
        display: flex;
        list-style: none;
        padding: 0 15px;
        background-color: #fbfbfb;
        border-bottom: 1px solid #e3e3e3;

        li{
          text-align: center;
          padding: 10px 15px;
          flex: 1;
          background: #fbfbfb;
          border: 1px solid #fbfbfb;
          border-bottom: 1px solid #e3e3e3;
          cursor: pointer;
          margin-bottom: -1px;
          display: flex;
          align-items: center;
          justify-content: center;

          i{
            margin-right: 15px;
          }

          &.is-active{
            background-color: white;
            border-color: #e3e3e3;
            border-top-color: #e3e3e3;
            border-bottom-color: white;
          }
        }
      }
      &-tab-content{
        flex: 1;
        position: relative;
      }

      &-tab-item{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right:0;
        overflow: auto;
        padding: 30px 15px;
      }

      &__fields{}
    }
    
    .form-preview {
      background-color: #fbfbfb;
      border-left: 1px solid #e3e3e3;
      padding: 30px;
      overflow: auto;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;

      &-wrap{
        position: relative;
        flex: 1;
      }
    }

    .form-help{
      color: #666;
      padding-top: 5px;
      font-style: italic;
    }

    .form-builder-action{
      padding: 10px 15px;
      border-top: 1px solid #e3e3e3;
      background-color: #fbfbfb;
    }

    .contact-form-field, .field-submit-button{
      background-color: white;
      font-weight: 700;
      border: 1px solid #e3e3e3;
      margin-bottom: -1px;

      .form-field-head{
        display: flex;
        align-items: center;
        span{
          flex: 1;
          cursor: grab;
          padding: 15px;
        }
        button{
          background-color: transparent;
          border: none;
          color: inherit;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: 10px;
          cursor: pointer;
          i{font-size: 18px;}

          &.delete-field{
            color: darken(red, 10%);
          }
        }
      }

      .form-field-setting{
        border-top: 1px solid #e3e3e3;
        padding: 15px;
        background-color: #fbfbfb
      }
      
      .form-field{
        display: flex;
        align-items: center;
        margin-bottom: 15px;

        &:last-child{margin-bottom: 0;}
        .form-label{
          width: 50%;
          font-weight: normal;
        }
        .form-control-body{
          flex: 1;
          text-align: right;
        }

        &-options{
          flex-direction: column;
          align-items: stretch;
          margin-bottom: 15px;

          .btn{
            margin-top: 15px;
          }

        }
      }

      .form-select-option{
        display: flex;
        padding: 5px;
        border: 1px solid #e3e3e3;

        input{
          flex: 1;
          border-radius: 4px;
          border: 1px solid #e3e3e3;
          padding: 5px 10px;
        }
        button{
          background-color: transparent;
          border: none;
        }
      }

    }

    .field-submit-button {
      border-left: 10px solid var(--primary)
    }

    .fields-empty {
      border: 2px dashed #e3e3e3;
      padding: 30px;
      text-align: center;
      margin-bottom: 30px;
    }

    .add-field{
      width: 100%;
    }

    .form{
      margin: 0 auto;
      margin-bottom: 30px;
      max-width: 640px;
      background-color: white;
      border: 1px solid #e3e3e3;
      padding: 40px;
      box-shadow: 0 3px 10px rgba(0,0,0,.1);
      position: relative;

      .field{
        padding: 0 15px;

        &-is-full{
          width: 100%;
        }

        &-is-half{
          width: 50%;
        }
      }
    }
    .success-message{
      background-color: white;
      padding: 40px;

      &.is-cover{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
      }

      &.is-top,&.is-bottom {
        // background-color: #ebfbee;
        border: 1px solid #2b8a3e;
        padding: 30px;
        position: relative;

        .close-message{
          top: 10px;
          right: 10px;
          width: 24px;
          height: 24px;
          i{font-size: 16px}
        }
      }
      &.is-top{ margin-bottom: 30px }
      &.is-bottom{ margin-top: 30px }

      .close-message{
        position: absolute;
        top: 15px;
        right: 15px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: darken(red,10%);
        color: white;
        border: none;
      }

    }
    .success-message-content{
      .ql-align-left   { text-align: left; }
      .ql-align-center { text-align: center; }
      .ql-align-right  { text-align: right; }
    }
  }

  .close-form-builder{
    background-color: none;
    border: none;
  }
  
</style>