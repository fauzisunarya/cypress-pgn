<template>
<div class="icl-editor__structure icl-editor__tab">
  <div class="icl-editor__block-structure">
    <div class="mb-4">
      <h3 class="panel-title"><i class="mdi mdi-view_day"></i> Konten Halaman</h3>
      <p>Atur konten dalam halaman web mu disini</p>
    </div>
    <keep-alive>
      <draggable
        class="structure-sections"
        v-if="pageBlocks.length"
        v-model="pageBlocks"
        handle=".block-drag-handle"
        v-bind="dragOptions"
        @start="drag = true"
        @end="drag = false"
        >
        <transition-group type="transition" :name="!drag ? 'flip-list': null">
          <div
            v-for="(block, index) in pageBlocks" 
            class="icl-editor-page-block"
            :class="{
              'is-selected': editorState.selectedBlock == block.id,
              'is-focused': activeBlock == index,
            }"
            :key="block.id"
            @click="setActiveBlock(block.id)"
            >
            <div class="block-drag-handle"><i class="mdi mdi-drag_handle"></i></div>
            <span class="block-name" @click="openOption(block.id)">{{block.name}}</span>
            <div class="block-options">
              <button data-tooltip="Simpan Blok" @click.stop="saveBlock(block)"><i class="mdi mdi-save"></i></button>
              <button data-tooltip="Hapus Blok" class="block-delete" @click.stop="removeBlock(index)"><i class="mdi mdi-delete"></i></button>
              <!-- <button @click.stop="activeBlock=index" class="block-options-trigger"><i class="mdi mdi-more_horiz"></i></button>
              <div class="block-options-menu">
              </div> -->
            </div>
          </div>
        </transition-group>
      </draggable>
    </keep-alive>

    <div class="text-center p-5" v-if="pageBlocks.length === 0">
      <h1>😉</h1>
      <div>
        <p>Anda belum mempunyai blok,<br/>mulai tambahkan blok pada halaman anda atau mulai dari template</p>
      </div>
      <button class="btn btn-outline-secondary d-inline-flex" @click="openTemplate"><i class="mdi mdi-format_paint mr-2"></i> Pilih Template</button>

    </div>
    
    <div class="d-flex mt-3 mb-3">
      <button class="add-block" @click="showBlockModal"><i class="mdi mdi-add"></i> Tambah Blok</button>
      <button class="remove-all ml-3" @click="removeAllBlock" v-show="pageBlocks.length" data-tooltip="Hapus semua block"><i class="mdi mdi-delete"></i></button>
    </div>
    
  </div>
  
  <transition name="pull-left">
    <div
      v-if="editorState.activeBlock"
      class="icl-editor__block-options">
      
      <div class="icl-editor__block-options-header">
        <button @click="closeBlockOptions"><i class="mdi mdi-arrow_back"></i></button>
        <h2>Pengaturan Blok</h2>
      </div>
      <div class="block-option-domain">
        <div :class="{'is-active': editorState.activeOptionDomain == 'content'}" @click="setActiveOptionDomain('content')"><i class="mdi mdi-format_shapes"></i><div>Konten</div></div>
        <div :class="{'is-active': editorState.activeOptionDomain == 'layout'}" @click="setActiveOptionDomain('layout')"><i class="mdi mdi-format_paint"></i><div>Tata Letak & Gaya</div></div>
      </div>
      <div class="icl-editor__block-options-content-wrap">
        
      
        <div class="icl-editor__block-options-content">
          <div v-if="editorState.activeOptionDomain == 'content'" class="block-options-content">
            <div
              class="block-option"
              :class="{'is-active':editorState.activeBlock == block.id}"
              v-for="block in pageBlocks"
              :key="block.id">
              <div class="block-option__field form-group">
                <div class="icl-ed-field icl-ed-field--horizontal">
                  <label class="form-label">ID Blok</label>
                  <input class="form-control" type="text" :value="`block-${block.id}`" readonly onClick="this.select()">
                </div>
              </div>
              <div class="block-option__field form-group">
                <div class="icl-ed-field icl-ed-field--horizontal">
                  <label class="form-label">Nama Blok</label>
                  <input class="form-control" type="text" v-model="block.name" @change="setDirty">
                </div>
              </div>
              <div class="block-option__field form-group spacer">
              </div>
              <template v-for="(field,index) in block.data">
                <div class="block-option__field form-group" :key="index" v-if="field.type !== 'empty'" :class="{'spacer': field.type=='spacer'}">
                  <div v-if="!field.hidden">
                    <component :is="`field-${field.type}`" :data="field" ></component>
                  </div>
                  <div class="form-desc" v-show="field.description">
                    {{field.description}}
                  </div>
                </div>
              </template>
            </div>
          </div>
          <div v-if="editorState.activeOptionDomain == 'layout'" class="block-options-style">
            <div
              class="block-option"
              :class="{'is-active':editorState.activeBlock == block.id}"
              v-for="block in pageBlocks"
              :key="block.id">
              <div class="block-option__field form-group" v-for="(field,index) in block.style" :key="index" :class="{'spacer': field.type=='spacer'}">
                <component :is="`field-${field.type}`" :data="field" ></component>
              </div>
              <div v-if="isEmpty(block.style)">
                <div>Block ini tidak memiliki opsi layout & style</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</div>
</template>

<script>
//<button class="add-block add-block-all" @click="addBlockAll"><i class="mdi mdi-add"></i> Tambah Semua Block</button>
import findIndex from 'lodash/findIndex'
import isEmpty from 'lodash/isEmpty'
import cloneDeep from 'lodash/cloneDeep'

import qs from 'qs'
import Axios from 'axios'
import draggable from 'vuedraggable'

// Import fields
import Text from '@/components/fields/Text'
import Slider from '@/components/fields/Slider'
import WYSWYG from "@/components/fields/wyswyg"
import Select from '@/components/fields/Select'
import Repeatable from '@/components/fields/Repeatable'
import RepeatableOnce from '@/components/fields/RepeatableOnce'
import RadioImage from '@/components/fields/RadioImage'
import RadioIcon from '@/components/fields/RadioIcon'
import Switch from '@/components/fields/Switch'
import Image from '@/components/fields/Image'
import Color from '@/components/fields/Color'
import Icon from '@/components/fields/Icon'
import Spacer from '@/components/fields/Spacer'
// import Product from '@/components/fields/Product'
import Directional from '@/components/fields/Directional'
import Background from '@/components/fields/Background'
import Gallery from '@/components/fields/Gallery'
import Button from '@/components/fields/Button'
import Overlay from '@/components/fields/Overlay'
import Divider from '@/components/fields/Divider'
import Form from '@/components/fields/Form'
import PackageLink from '@/components/fields/PackageLink'
import Empty from '@/components/fields/Empty'

export default {
  name: "structure",
  components: {
    draggable,

    'field-text'        : Text,
    'field-wyswyg'      : WYSWYG,
    'field-select'      : Select,
    'field-repeatable'  : Repeatable,
    'field-repeatable-once'  : RepeatableOnce,
    'field-radio-image' : RadioImage,
    'field-radio-icon'  : RadioIcon,
    'field-switch'      : Switch,
    'field-image'       : Image,
    'field-color'       : Color,
    'field-icon'        : Icon,
    'field-slider'      : Slider,
    // 'field-product'     : Product,
    'field-spacer'      : Spacer,
    'field-directional' : Directional,
    'field-background'  : Background,
    'field-gallery'     : Gallery,
    'field-button'      : Button,
    'field-overlay'     : Overlay,
    'field-divider'     : Divider,
    'field-form'        : Form,
    'field-package-link': PackageLink,
    'field-empty'       : Empty
  },
  data() {
    return {
      blockOption : false,
      drag        : false,
      activeBlock : null,
      componentOptions    : {
        group:{
          name:'component',
          pull:'clone'
        },
        sort: false
      },
    }
  },
  computed: {
    header01Block() {
      return this.editorState.blocks.find((block) => block.blockID === "header-01");
    },
    footer01Block() {
      return this.editorState.blocks.find((block) => block.blockID === "footer-01");
    },
    indexCatalogBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "catalog-01");
    },
    detailCatalogBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "catalog-02");
    },
    indexArticleBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "post-01");
    },
    detailArticleBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "post-04");
    },
    editorState(){
      return this.$store.state.editorState
    },
    pageBlocks:{
      get() {
        const index = findIndex(this.$store.state.project.pages, i=> i.id == this.$store.state.editing)
        if (index !== -1)
          return this.$store.state.project.pages[index].blocks
        else
          return []
      },
      set(value) {
        this.$store.commit( 'pageBlocks', value )
      }
    },
    dragOptions() {
      return {
        animation: 200,
        group: "structure",
        disabled: false,
        ghostClass: "ghost"
      };
    },
  },
  methods: {
    setDirty(){
      this.$store.commit('dirty', true);
    },
    isEmpty(data){
      return isEmpty(data)
    },
    openTemplate(){
      this.$templateModal.show({
        onSelected: this.onSelectedTemplate
      })
    },
    onSelectedTemplate(data){
      
      if ( data.title == "__blank-template" )
        data = {
          'title'       : 'New Page',
          'description' : 'Page description',
          'label'       : 'New page',
          'blocks'      : [],
          'slug'        : 'new-page'
        }
      
      if ( data.title == "product-template" ) {
        data = [
          {
            'title'       : 'Daftar Produk',
            'description' : 'Deskripsi Daftar Produk',
            'label'       : 'Daftar Produk',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.indexCatalogBlock,
                id: this.$uuid.v1(),
                name: this.indexCatalogBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'daftar-produk-ukm'
          },
          {
            'title'       : 'Detail Produk',
            'description' : 'Deskripsi Detail Produk',
            'label'       : 'Detail Produk',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.detailCatalogBlock,
                id: this.$uuid.v1(),
                name: this.detailCatalogBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'detail-produk-ukm'
          },
        ];
      }

      if ( data.title == "article-template" ) {
        data = [
          {
            'title'       : 'Daftar Artikel',
            'description' : 'Deskripsi Daftar Artikel',
            'label'       : 'Daftar Artikel',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.indexArticleBlock,
                id: this.$uuid.v1(),
                name: this.indexArticleBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'daftar-artikel-ukm'
          },
          {
            'title'       : 'Detail Artikel',
            'description' : 'Deskripsi Detail Artikel',
            'label'       : 'Detail Artikel',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.detailArticleBlock,
                id: this.$uuid.v1(),
                name: this.detailArticleBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'detail-artikel-ukm'
          },
        ];
      }
      
      if ( data.type == "user-template" ) {
        // return console.log( data );
        data = {
          'title'       : data.name,
          'description' : 'Page description',
          'label'       : data.name,
          'blocks'      : data.blocks,
          'slug'        : 'new-page'
        }
        delete data.id
      }

      // return console.log(cloneDeep(data));
      return this.$store.dispatch( 'loadTemplate', data );
      // return this.$store.commit('UPDATE_PAGE', data.blocks );
    },
    showBlockModal(){
      if (! this.$store.state.project.pages.length ) {
        this.$swal({
          title: 'Halaman Kosong',
          text: "Anda belum memiliki halaman, buat satu dan tambahkan block sekarang",
          type: 'warning',
        })

        this.$store.dispatch('setSidebarTab', 'page' );
      }
      this.$blockModal.show()
      // setTimeout(()=>{
      //   this.$refs.blocks && this.$refs.blocks.layout('masonry');
      // },150)
    },
    addBlockAll(){
      // console.log(  );
      // var cloned = cloneDeep(block)
      // cloned.id = this.$uuid.v1()
      // cloned.name = block.title
      // this.editorState.activeBlock = cloned.id
      // this.$store.dispatch('addPageBlock', cloned)

      const blocks = cloneDeep( this.$store.state.editorState.blocks );
      blocks.map( block => {
        block.id = this.$uuid.v1()
        block.name = block.title
      } );

      this.$store.commit('UPDATE_PAGE', blocks );
    },
    openOption(id) {
      this.$store.commit('ACTIVE_BLOCK', id)
      this.blockOption = true;
      // this.$store.dispatch('setSidebarTab', 'option' );
    },
    setActiveBlock(id){
      this.$store.commit('ACTIVE_BLOCK', id);
    },
    setActiveOptionDomain( domain ){
      this.$store.commit('activeOptionDomain', domain)
    },
    closeBlockOptions(){
      this.$store.commit('ACTIVE_BLOCK', null)
    },
    saveBlock(block){
      const blockData = cloneDeep(block);

      this.$swal({
        title: 'Save this block',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Save',
        showLoaderOnConfirm: true,
      }).then((result) => {

        if (result.value) {

          const data = {
            title  : result.value,
            blocks : JSON.stringify(blockData),
            ...this.$root.config.extra
          };

          const options = {
            method          : 'POST',
            headers         : { 'content-type': 'application/x-www-form-urlencoded' },
            data            : qs.stringify(data),
            url             : this.$root.config.api+'/blocks/user',
            withCredentials : true
          };
          

          Axios(options).then( () => {
            this.saving = false
            this.$notify({
              group: 'builder',
              title: 'Block saved',
              text: `Your block is saved, now you can use it across the pages`,
              type: 'success'
            });
            this.getSavedBlocks();
          })
        }
      });
    },
    getSavedBlocks(){
      Axios.get(this.$root.config.api + "/blocks/user")
        .then( response => {
          this.$store.commit('UPDATE_USER_BLOCK', response.data.content);
        } )
    },
    removeAllBlock() {
      this.$swal({
        title: 'Hapus Semua Blok di halaman ini?',
        text: "Anda tidak dapat mengembalikannya lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya hapus saja!'
      }).then((result) => {
        // console.log(result);
        if (result.value) {
          this.editorState.activeBlock = null
          this.$store.commit('REMOVE_ALL_BLOCK')
        }
      })
    },
    removeBlock(index){
      this.$swal({
        title: 'Hapus Blok ini?',
        text: "Anda tidak dapat mengembalikannya lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya hapus saja!'
      }).then((result) => {
        // console.log(result);
        if (result.value) {
          this.editorState.activeBlock = null
          this.$store.commit('removeBlock', index)
        }
      })
    },
  }
}
</script>

<style lang="less">
.icl-editor{

  &__block-structure{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    padding: 15px;
  }
  &-page-block{
    position: relative;
    display: flex;
    background-color: white;
    box-shadow: 0 0 1px;
    transition: background-color .3s ease;

    &:hover{
      background-color: #f8f8f8;
    }

    &.is-selected {
      border-color: var(--blue);
      outline: 2px solid var(--blue);
      z-index: 1;
    }
    &.is-focused{
      z-index: 1;
    }
    // &:hover{
    //   z-index: 1;
    // }
    .block-name{
      flex: 1;
      border: none;
      outline: none;
      padding: 15px 0;
      cursor: pointer;
    }
    
    .block-drag-handle{
      display: flex;
      align-items: center;
      padding: 5px 10px;
      justify-content: center;
      cursor: move;
      opacity: 0.6;
    }
    .block-option-btn{
      background-color: transparent;
      border: none;
      color: #888;
      display: flex;
      align-items: center;
    }

    .block-options{
      position: relative;
      display: flex;
      opacity: 0;
      pointer-events: none;
      transition: .3s ease;
      button {
        background-color: transparent;
        border: none;
        color: #666;
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;

        
        &.block-delete:hover{
          color: var(--red);
        }

      }
      &-trigger{
        border: none;
        background-color:transparent;
        display: flex;
        align-items: center;
        &:focus{
          background-color: var(--primary);
          color: white;
          + .block-options-menu{
            opacity: 1;
            visibility: visible;
          }
        } 
      }
      &-menu{
        position: absolute;
        top: 100%;
        right: 0;
        border: 1px solid #e3e3e3;
        min-width: 150px;
        background-color: white;
        padding: 5px;
        border-radius: 3px;
        box-shadow: 0 10px 30px -3px rgba(0,0,0,.1);
        // display: none;
        opacity: 0;
        visibility: hidden;
        transition: .3s ease;

        &:before{
          content:" ";
          width: 100%;
          height: 10px;
          position: absolute;
          bottom: 100%;
          right: 0;
        }

        &:after{
          content:" ";
          width:0;
          border-style: solid;
          border-color: transparent transparent white;
          border-width: 5px;
          position: absolute;
          bottom: 100%;
          right: 15px;
        }

        div{
          padding: 10px;
          display: flex;
          align-items: center;
          cursor: pointer;
          transition: .3s ease;
          border-radius:3px;
          &:hover{
            background-color: var(--blue);
            color: white;

          }
        }
        i{
          margin-right: 10px;
          font-size: 1.5em
        }
      }
    }

    &:hover .block-options{
      opacity: 1;
      pointer-events: auto;
    }
  }
  &__tab{
    display: none;
    padding: 15px;

    &.is-active{
      display: block;
    }
  }
  &__block-options{
    padding: 0;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    z-index: 1;
    // background-color: #e9ecf1;
    background-color: #f8f9fa;
    background-color: #fff;
    transition: .3s ease;
    transform: translateX(0);
    box-shadow: -1px 0 1px rgba(0,0,0,.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;

    &-header{
      display: flex;
      background-color: white;
      z-index: 1;
      position: relative;
      box-shadow: 0 1px 1px rgba(0,0,0,.1);
      padding: 10px 15px;
      align-items: center;

      button {
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      h2{
        font-size: 18px;
        padding-left: 10px;
        margin: 0;
        line-height: 1;
      }
    }
    &-content-wrap{
      flex-grow: 1;
      overflow: auto;
      margin-top: 1px;
    }
  }
}

.block-option-domain{
  display: flex;
  > div{
    padding: 15px 10px;
    flex: 1;
    display: flex;
    // flex-direction: column;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    background-color: #e7e9eb;

    &.is-active{
      background-color: white;
    }
  }
  i{
    margin-right: 5px;
  }
}

.icl-ed-field{
  &--horizontal{
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    > label{
      // width: 200px;
      flex: 1;
      margin: 0!important;
    }
    .form-control,
    .input-group{
      max-width: 160px;
    }
    .icon-field{
      display: flex;
      justify-content: space-between;
      align-items: center;
      label{
        margin: 0!important;
      }
    }
    .field-icon-radio{
      display: inline-flex;
    }
    .field-select{
      width: 160px;
    }
  }

  .vue-codemirror{
    max-width: 100%;
    width: 100%;
    border: 1px solid #e3e3e3;
  }
}
.pull-left-enter-active, .pull-left-leave-active{
}
.pull-left-enter, .pull-left-leave-to /* .fade-leave-active below version 2.1.8 */ {
  transform: translateX(100%);
}
.block-option{
  display: none;
  // background-color: white;
  padding: 20px 0;
  // 
  &.is-active{
    display: block;
  }

  &__field{
    padding: 15px 20px;
    margin-bottom: 0;
    transition: .3s ease;
    border-left: 2px solid transparent;
    // border-bottom: 1px solid #e3e3e3;
    &:hover,&.is-active{
      background-color: #fbfbfb;
      border-left-color: var(--primary)
    }  

    &.spacer{
      background-color: #f8f9fa;
      margin: -1px -20px 0;
      border-top: 1px solid rgba(0,0,0,.1);
      border-bottom: 1px solid rgba(0,0,0,.1)
    }
    &:after{
      content: " ";
      clear: both;
      display: block;
    }
    label{
      // text-transform: uppercase;
      font-weight: 500;
      font-size: 14px;
      margin-bottom: 15px;
      opacity: 0.8;
    }
    // &:not(:last-child){border-bottom: 1px solid rgba(0,0,0,.1)}
  }
}

[data-tooltip] {
  position: relative;
  outline: none;
  &:after {
    content: attr(data-tooltip);
    position: absolute;
    top: 0;
    bottom: 0;
    height: 2.5em;
    margin: auto 10px auto auto;
    background-color: #333;
    color: white;
    padding: 3px 10px;
    font-size: 13px;
    white-space: nowrap;
    right: 100%;
    border-radius: 3px;
    opacity: 0;
    pointer-events: none;
    transition: .3s ease;
    display: flex;
    align-items: center;
  }

  &:hover{
    
    &:after { opacity: 1; }
  }
}
.form-desc{
  color: #666;
  padding-top: 15px;
  font-size: .95em;
}
.form-notice{
  width: 100%;
  color: #666;
  margin-top: 15px;
  font-size: .95em;
  background-color: #fff0c4;
  border: 1px solid var(--yellow);
  padding: 5px;
  border-radius: 3px
}
.mlb-field {
  .form-control{
    font-size: 14px;
    height: auto;
    min-height: calc(1.5em + .75rem + 2px);
  }
  select.form-control {
    height: calc(1.5em + .75rem + 2px);
  }
}
.add-block,.remove-all{
  
  border: 1px solid rgba(0,0,0,0.2);
  // margin-top: 15px;
  border-radius: 3px;
  padding: 10px 15px;
  text-transform: uppercase;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: white;
  color: #666;
  cursor: pointer;
  outline: none;
  transition: .3s ease;

  &:focus,&:active{ outline: none }

  i{
    margin-right: 15px;
  }
}
.add-block{ 
  width: 100%;
  &:hover{
    background-color: var(--green);
    color: white;
  }
}
.remove-all{
  &:hover {
    background-color: var(--red);
    color: white;
    
  }
  i{ margin-right: 0 }
}
.button i,
.btn i{
  vertical-align: middle;
  line-height: 1;
  margin: 0 5px;
}
</style>