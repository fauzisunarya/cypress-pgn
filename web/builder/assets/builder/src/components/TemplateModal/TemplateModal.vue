<template>
  <transition name="fade">
    <div class="icl-editor__template-modal" v-if="visible">
      <div class="icl-editor__template-modal-window">
        <!-- <div class="icl-editor__template-modal-sidebar">
          <input type="text" @keyup="debounceFilter" placeholder="Search block">
          <div class="block-categories">
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('*')" value="*"><span class="label">All Blocks</span><span class="count">{{this.blocks.length}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="header"><span class="label">Headers</span><span class="count">{{getBlockCount('header')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="content"><span class="label">Contents</span><span class="count">{{getBlockCount('content')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="call-to-action"><span class="label">Call to action</span><span class="count">{{getBlockCount('call-to-action')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="feature"><span class="label">Features</span><span class="count">{{getBlockCount('feature')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="contact"><span class="label">Contacts</span><span class="count">{{getBlockCount('contact')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="form"><span class="label">Forms</span><span class="count">{{getBlockCount('form')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="pricing"><span class="label">Pricings</span><span class="count">{{getBlockCount('pricing')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="team"><span class="label">Teams</span><span class="count">{{getBlockCount('team')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="testimonial"><span class="label">Testimonials</span><span class="count">{{getBlockCount('testimonial')}}</span></label>
            <label><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="footer"><span class="label">Footers</span><span class="count">{{getBlockCount('footer')}}</span></label>
          </div>
        </div> -->
        <div class="icl-editor__template-modal-header">
          <div class="icl-editor__template-modal-tab">
            <span @click.prevent="setBlockModalTab('template')" :class="{'is-active': blockModalTab == 'template' }"><i class="mdi mdi-format_paint"></i> Template</span>
            <span @click.prevent="setBlockModalTab('savedTemplate')" :class="{'is-active': blockModalTab == 'savedTemplate' }"><i class="mdi mdi-bookmark"></i> Template tersimpan</span>
          </div>

          <button @click="closeTemplateModal"><i class="mdi mdi-close"></i></button>
        </div>
        <div class="icl-editor__template-modal-content">
          
          <keep-alive>
            <div v-if="blockModalTab == 'template'" style="width:100%">
              <div class="template-grid-wrap">
                <div class="template-grid">
                  <!-- empty page -->
                  <div class="template-item template-item--blank" @click="onSelect({
                      title: '__blank-template',
                      blocks: []
                    })">
                    <div class="template-item-inner">
                      <i class="mdi mdi-insert_drive_file"></i>
                      <span>Halaman Kosong</span>
                    </div> <!-- .template-item-inner -->
                  </div>

                  <!-- product page -->
                  <!-- <div
                    title="Membuat halaman Daftar Produk dan Detail Produk."
                    class="template-item template-item--blank"
                    @click="onSelect({
                      title: 'product-template',
                      blocks: []
                    })">
                    <div class="template-item-inner">
                      <i class="mdi mdi-shopping_cart"></i>
                      <span>Halaman Produk</span>
                    </div> .template-item-inner -->
                  <!-- </div> -->

                  <!-- article page -->
                  <!-- <div
                    title="Membuat halaman Daftar Artikel dan Detail Artikel."
                    class="template-item template-item--blank"
                    @click="onSelect({
                      title: 'article-template',
                      blocks: []
                    })">
                    <div class="template-item-inner">
                      <i class="mdi mdi-description"></i>
                      <span>Halaman Artikel</span>
                    </div> .template-item-inner -->
                  <!-- </div> -->

                  <!-- template pages -->
                  <div
                    class="template-item"
                    v-for="(template, index) in templates"
                    :key="`item-${index}`"
                    @click="viewTemplate(template)">
                    <div class="template-item-inner">
                      <div class="template-image" :style="{backgroundImage: `url(${template.image})`}"></div>
                      <div class="template-detail">
                        <div class="template-title">{{template.title}}</div>
                        <div class="template-page-count">{{template.pages.length}} Halaman</div>
                      </div>
                    </div> <!-- .template-item-inner -->
                  </div>
                </div>
                
              </div>
              <transition name="pull-left">
                <div class="template-pages-wrap" v-if="activeTemplate">
                  <button @click="closeTemplate" class="btn btn-primary back-to-list"><i class="mdi mdi-arrow_back"></i> Kembali</button>
                  <div class="template-pages">
                    <div
                      class="template-item"
                      v-for="(page, i) in activeTemplate.pages"
                      :key="page.title + i"
                      @click="onSelect(page)">
                      <div class="template-item-inner">
                        <div class="template-image" :style="{backgroundImage: `url(${page.image})`}"></div>
                        <div class="template-detail">
                          <div class="template-title">{{page.title}}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </transition>
            </div>
          </keep-alive>

          <keep-alive>
            <div v-if="blockModalTab == 'savedTemplate'" style="width:100%">
              
              <ul class="template-list" v-if="savedTemplate">
                <li v-for="(template, index) in savedTemplate" :key="index" @click="onSelectUserTemplate(template)">
                  <span class="name">{{template.name}}</span>
                  <button class="delete_template" @click.stop.prevent="deleteTemplate(template.id)"><i class="mdi mdi-delete"></i></button>
                </li>
              </ul>
              <div v-else class="template-empty">
                <h2>Template Kosong</h2>
                <p>Anda belum menyimpan template buatan sendiri</p>
              </div>
            </div>
          </keep-alive>

        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import Modal from './index'
import Axios from 'axios'
import imagesLoaded from 'vue-images-loaded'
import debounce from 'lodash/debounce'
import countBy from 'lodash/countBy'

export default {
  name: 'template-modal',
  directives: {
    imagesLoaded
  },
  data() {
    return {
      visible             : false,
      blockModalTab       : 'template',
      activeTemplate      : null,
      activeBlockCategory : '*',
      filterText          : "",
      options: {
        transitionDuration: 0,
        layout: 'masonry',
        hiddenStyle: {
          opacity: 0,
          transform: 'translateY(15px)'
        },
        visibleStyle: {
          opacity: 1,
          transform: 'translateY(0)'
        },
        getFilterData: {
          "*": function() {
            return true;
          },
          header: function(el) {
            return el.category === 'header'
          },
          content: function(el) {
            return el.category === 'content'
          },
          'call-to-action': function(el) {
            return el.category == 'call=-to-action'
          },
          feature: function(el) {
            return el.category === 'feature'
          },
          contact: function(el) {
            return el.category === 'contact'
          },
          form: function(el) {
            return el.category === 'form'
          },
          pricing: function(el) {
            return el.category === 'pricing'
          },
          team: function(el) {
            return el.category === 'team'
          },
          testimonial: function(el) {
            return el.category === 'testimonial'
          },
          footer: function(el) {
            return el.category === 'footer'
          },
          filterByCategory: (el) => {
            return el.category === this.activeBlockCategory;
          },
          filterByText: (el) => {
            return el.title.toLowerCase().includes(this.filterText.toLowerCase());
          }
        },
      },
      
      onSelected: {}
    }
  },
  computed:{
    templates(){
      return this.$store.state.templates
    },
    savedTemplate(){
      return this.$store.state.project.user_templates
    },
    editorState(){
      return this.$store.state.editorState
    },
    savedBlocks(){
      return this.$store.state.savedBlocks
    },
    blocks(){
      return this.$store.state.editorState.blocks
    },
  },
  methods: {
    viewTemplate(template){
      this.activeTemplate = template
    },
    closeTemplate(){
      this.activeTemplate = null
    },
    setBlockModalTab(tab){
      this.blockModalTab = tab
    },
    debounceFilter: debounce(function(event){
      this.filterText = event.target.value;
      this.filter('filterByText');
    },150),
    show(params) { 
      this.visible = true
      this.onSelected = params.onSelected
    },
    onSelect(data){
      const quota = this.$store.state.project.quota;
      const currentPages = this.$store.state.project.pages.length;

      if (
        // if user try to create product-template or article-template
        (data.title === "product-template" || data.title === "article-template")
        &&
        // if insufficient quota
        (currentPages + 2 > quota)
      ) {
        return this.$swal.fire({
          title: "Kuota halaman anda tidak mencukupi",
          text: `Untuk membuat Halaman ${data.title === "product-template" ? "Produk" : "Artikel"} dibutuhkan 2 slot halaman`,
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });
      }

      this.onSelected(data)
      this.activeTemplate = null
      this.closeTemplateModal();
    },
    onSelectUserTemplate(data){
      this.onSelected({
        type: "user-template",
        ...data,
      })
      this.closeTemplateModal();
    },
    closeTemplateModal(){
      this.visible= false;
    },
    getBlockCount(category){
      const index = countBy(this.blocks,i=>i.category == category);
      return index.true || ''
    },
    layout () {
      this.$refs.blocks && this.$refs.blocks.layout('masonry');
    },
    filter(key) {
      this.blockModalTab = 'block';
      this.$refs.blocks && this.$refs.blocks.filter(key);
    },
    closeByEsc(event) {
      var key = event.key || event.keyCode;
      if ( key == "Escape" ) {
        this.closeTemplateModal();
      }
    },
    deleteTemplate(id) {
      const options = {
        method          : 'DELETE',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        url             : this.$root.config.api+'/templates/user/'+id,
        withCredentials : true
      };

      this.$swal({
        title: 'Hapus Template ini?',
        text: "Anda tidak akan bisa mengembalikannya lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya hapus saja!'
      }).then((result) => {
        
        if (result.value) {
          this.editorState.activeBlock = null
          return Axios(options)
            .then(()=>{
              this.$store.commit('REMOVE_USER_TEMPLATE',id);

              this.$notify({
                group : 'builder',
                title : 'Berhasil',
                text  : `Template berhasil dihapus`,
                type  : 'success'
              });

            })
            .catch(error=>{
              this.$notify({
                group : 'builder',
                title : 'Gagal',
                text  : `Gagal menghapus Template. \n kode error: ${error}`,
                type  : 'success'
              });
            })
        }
      })

    },
  },
  mounted(){
    document.addEventListener( 'keyup', this.closeByEsc, false );
  },
  beforeMount(){
    Modal.event.$on('showTemplateModal', (params)=>{
      this.show(params)
    })
  },
}
</script>

<style lang="less">
  .icl-editor-design-block{
    // border: 2px solid #e3e3e3;
    margin-bottom: 15px;
    display: none;
    position: relative;
    // padding: 5px;
    // background-color: #333;

    img{
      max-width: 100%;
      width: 100%;
      display: block;
    }

    figcaption{
      text-align: left;
      padding: 10px;
      font-weight: 700;
      font-size: 12px;
      background-color: #FBFBFB;
      position: absolute;
      bottom: 0;
      border-top-right-radius: 5px;
      opacity: 0;
      transition: .3s ease;
    }
    &:hover figcaption{
      opacity: 1;
    }

    &.is-active{
      display: block;
    }
  }

  .icl-editor__template-modal{
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background-color: rgba(0,0,0,.8);
    // backdrop-filter: blur(2px);
    &-window{
      max-width: 990px;
      height: 768px;
      max-height: 90vh;
      background-color: white;
      box-shadow: 0 10px 30px rgba(0,0,0,.5);
      border-radius: 6px;
      overflow: hidden;
      position: absolute;
      left: 15px;
      right: 15px;
      top: 0;
      bottom: 0;
      margin: auto;
      // padding-left: 250px;
    }
    &-sidebar{
      width: 250px;
      background-color: #333;
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      padding: 10px 15px;
      color: white;

      > input {
        width: 100%;
        border-radius: 3px;
        border: none;
        padding: 5px 10px;
      }

      .block-categories{
        padding-top: 30px;
        label{
          margin-bottom: 1em;
        }
      }

      label{
        display: flex;
        justify-content: space-between;
        cursor: pointer;
        font-size: 14px;
        input{
          display: none;

          &:checked + .label {
            color: var(--cyan);
          }
        }
      }
    }
    &-header{
      display: flex;
      justify-content: space-between;
      box-shadow: 0 1px 1px rgba(0,0,0,.1);
      z-index: 1;
      position: relative;

      button{
        margin-left: auto;
        background-color: darken(red, 10%);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
      }
    }
    &-tab{
      display: flex;
      padding: 10px;
      span{
        padding         : 5px 15px;
        display         : flex;
        align-items     : center;
        color           : inherit;
        border-radius   : 3px;
        text-decoration : none;
        cursor          : pointer;
        font-weight     : 700;

        i{
          opacity: .8;
          margin-right: 10px;
        }

        &.is-active{
          background-color: var(--primary);
          color: white;
        }
      }
    }
    &-content {
      display: flex;
      flex-wrap: wrap;
      overflow: auto;
      position: absolute;
      right: 0;
      left: 250px;
      left: 0;
      bottom: 0;
      top: 54px;
      padding: 5px;
      background-color: #e9ecf1
    }
  }
  .masonry{
    width: 100%;
    &-item{
      width: 33.33%;
      padding: 10px;
      cursor: pointer;

      .icl-editor-design-block{
        display: block;
        width: 100%;
        margin-bottom: 0;
        transition: .3s ease;
        border: none;
        border-radius: 3px;
      }
      &:hover{
        z-index: 1;
        .icl-editor-design-block{
          box-shadow: 0 20px 50px -10px rgba(0,0,0,.3);
        }
      }
      &:active{
       .icl-editor-design-block{
          box-shadow: 0 20px 50px -10px rgba(0,0,0,.3);
        } 
      }
    }
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
  .template-list{
    list-style: none;
    margin: 30px;
    padding: 0;

    li{
      background-color: white;
      font-weight: 700;
      cursor: pointer;
      position: relative;
      transition: .3s ease;
      display: flex;

      &:not(:last-child){
        border-bottom: 1px solid #e3e3e3;
      }

      &:first-child{
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
      }
      &:last-child{
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
      }

      &:hover{
        z-index: 1;
        box-shadow: 0 10px 30px rgba(0,0,0,.1);
      }

      .name{
        flex: 1;
        padding: 15px;
      }
      button{
        background-color: transparent;
        border: none;
        i{
          line-height: 1;
        }
        &.delete_block{
          color: darken(red,5%);
        }
      }
    }
  }
  .template-grid{
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    &-wrap{
      overflow: auto;
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      padding: 10px;
    }
  }
  .template-item{
    width: 25%;
    padding: 10px;
    cursor: pointer;
    &-inner{
      transition: .3s ease;
      box-shadow: 0 0 0 1px rgba(0,0,0,.1), 0 1px 1px rgba(0,0,0,.1);
      border-radius: 5px;
      overflow: hidden;
    }
    &:not(.template-item--blank) .template-item-inner:hover{
      transform: translateY(-5px);
      box-shadow: 0 10px 30px -5px rgba(0,0,0,.2);
    }
    .template-image{
      padding-bottom: 80%;
      background-size: 100% auto;
      overflow: hidden;
      transition: .5s ease;
      background-position: top;
    }

    &:hover .template-image{
      background-position: bottom;
      transition: 10s ease;
    }

    .template-detail{
      padding: 10px 15px;
      background-color: white;
    }
    
    .template-title{
      font-weight: 700;
    }
    .template-page-count{
      font-size: 14px
    }

    &--blank{
      .template-item-inner{
        min-height: 240px;
        border: 2px dashed rgba(0,0,0,.4);
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: none;
      }
      &:hover .template-item-inner{
        background-color: white;
      }
      i{
        width: 48px;
        height: 48px;
        font-size: 48px;
        margin-bottom: 20px;
      }
    }
  }
  .template-pages{
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;

    &-wrap{
      background-color: #e9ecf1;
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      transition: .3s ease-out;
      transform: translateX(0);
      padding: 60px 10px 10px;
      overflow: auto;
    }

    .template-item .template-image{
      padding-bottom: 67%     
    }
  }
  .pull-left-enter, .pull-left-leave-to /* .fade-leave-active below version 2.1.8 */ {
    transform: translateX(100%);
  }
  .back-to-list{
    position: absolute;
    display: flex;
    align-items: center;
    top: 15px;
    left: 20px;
    i{
      font-size: 1.4em;
      margin-right: 5px;
    }
  }
  .template-empty{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;

  }
</style>