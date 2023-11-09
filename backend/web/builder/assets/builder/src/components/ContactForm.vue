<template>
  <form ref="form" class="icl-form-builder icl-form" @submit.prevent="processForm" v-if="formData.fields && formData.fields.length">
    <div :class="`field field-is-${field.size}`" v-for="field in formData.fields || fields">

      <!-- show otherfield -->
      <template v-if="field.otherfield.show">
        <template v-if="field.field == 'coordinate'">
          <label v-if="showLabel" class="label">{{field.otherfield.label}} <span v-if="field.required" style="color:red">*</span></label>
          <div class="control">
            <textarea style="margin-bottom:15px" rows=4 class="textarea" :name="field.otherfield.name" :required="field.required" :placeholder="field.otherfield.placeholder"/>
          </div>
        </template>
      </template>

      <!-- preview map for coordinate field -->
      <img v-if="field.field == 'coordinate'" width="100%" :height="field.size == 'full' ? '300px' : '150px'" style="margin-bottom:15px" src="/builder/assets/builder/src/assets/preview-map.png">

      <label v-if="showLabel" class="label">{{field.label}} <span v-if="field.required" style="color:red">*</span></label>
      <div class="control">

        <input :name="field.name" class="input" :type="field.type" v-if="field.field == 'input'" :required="field.required"  :placeholder="field.placeholder"/>

        <!-- input file or take photo (ktp & selfie) -->
        <input :name="field.name" :class="`input input-file-${field.type}`" type="file" v-if="field.field == 'file' && field.type == 'file'" />
        <button type='button' class='mt-2' v-if="field.field == 'file' && ['ktp', 'selfie'].includes(field.type)">Take photo</button>

        <textarea rows=4 class="textarea" :type="field.type" v-if="field.field == 'textarea'" :required="field.required" :placeholder="field.placeholder"/>

        <div class="select is-fullwidth" v-if="field.field == 'select'">
          <select :name="field.name" :required="field.required">
            <option value="">{{field.placeholder}}</option>
            <option v-for="option in field.options" :value="option.value">{{option.value}}</option>
          </select>
        </div>

        <div class="checkbox-group" v-if="field.field == 'checkbox'">
          <label v-for="option in field.options">
            <input type="checkbox" :name="field.name+'[]'" :value="option.value">
            <span>{{option.value}}</span>
          </label>
        </div>

        <div class="checkbox-group" v-if="field.field == 'radio'">
          <label v-for="option in field.options">
            <input type="radio" :name="field.name" :value="option.value">
            <span>{{option.value}}</span>
          </label>
        </div>

        <!-- input disabled for coordinate value (will be selected from map) -->
        <input v-if="field.field == 'coordinate'" :name="field.name" class="input" type="text"  :required="field.required"  :placeholder="field.placeholder" disabled/>

        <div class="select is-fullwidth" v-if="field.field == 'package'">
          <select :name="field.name" :required="field.required">
            <option value="">{{field.placeholder}}</option>
          </select>
        </div>

        <template v-if="field.field == 'signature'">
          <div id="sig-field" style="width: 100%; height: 200px; display: inline-block; border: 1px solid #a0a0a0;"></div>
          <button id="clear-sig" style="margin-top:10px">Clear Signature</button>
          <textarea style="display:none" />
        </template>
      </div>

    </div>
    <div :class="`form-submit-control field field-submit field-is-full has-text-${formData.submit.position} text-${formData.submit.position}`">
      <button type="submit" class="button is-primary">{{formData.submit.label}}</button>
    </div>
  </form>
  <div v-else class="has-text-centered">
    <div>Formulir ini kosong, Silahkan untuk mengeditnya terlebih dahulu</div>
  </div>
</template>

<script>
import findIndex from 'lodash/findIndex'
export default {
  props: {
    data:Object,
    id:[String, Number],
    onSubmit:Function,
    showLabel: {
      type: Boolean,
      default: true
    }
  },
  mounted(){
    const index  = findIndex( this.$store.state.project.forms, item=>item.form_id == this.id )
    // const scheme = JSON.parse( this.$store.state.project.forms[index].form_scheme );

    // return console.log(index);


    this.$store.subscribe( (mutation) => {
      if ( mutation.type == "UPDATE_FORM" ) {
        this.$forceUpdate();
      }
    } )
  },
  computed: {
    formData() {
      const defaultForm = {
        fields  : [
          {
            label: "Nama",
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
            label: "Email",
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
        ],
        submit  : {
          label: 'Send',
          position: 'center'
        },
        actions : {
          position: 'cover',
          message : 'Halo, terima kasih telah menghubungi kami, kami akan mengontak anda kembali',
        }
      }

      if ( this.data ) {
        return this.data
      } else {

        if ( this.id ) {
          const index  = findIndex( this.$store.state.project.forms, item=>item.form_id == this.id )

          if (index < 0 ) return {fields:[]}

          const scheme = JSON.parse( this.$store.state.project.forms[index].form_scheme );

          // console.log( this.id )

          return {
            fields  : scheme.fields,
            submit  : scheme.submit,
            actions : scheme.actions
          }
        } else {
          // User system form
          return defaultForm
        }
      }
    }
  },
  methods: {
    processForm(event) {
      const form = this.$refs.form;
      const data = new FormData( form );

      if ( typeof this.onSubmit == 'function' ) {
        this.onSubmit();
      }

    }
  }
}
</script>

<style lang="less">
.icl-form-builder{
  margin: 0 -15px;
  display: flex;
  flex-wrap: wrap;

  .field{
    padding: 0 15px;
    margin-bottom: 15px;
    &:last-child{ margin-bottom: 0; }

    label {
      font-weight: 700;
    }

    .input,.textarea, .select{
      width: 100%;
      padding: 10px;
      border-radius: 4px;
      background-color: #fff;
      border: 1px solid #dbdbdb;
      border-radius: 4px;
      color: inherit;
    }

    .select {
      padding: 0;
      select {
        -webkit-appearance: none;
        padding: 10px;
        border: none;
        background-color: transparent;
        width: 100%;
      }
    }

    &-is-full{
      width: 100%;
    }

    &-is-half{
      width: 50%;
    }
  }

  .checkbox-group {
    label {
      display: block;
      font-weight: normal;

      input{
        margin-right: 15px;
      }
    }
  }

  .button{
    background: #007bff;
    color: white;
    border-radius: 4px;
  }
}
</style>