<template>
  <div class="icl-editor__preview">
    <div class="icl-editor__preview-frame"
      :class="{'device-desktop': this.$store.state.editorState.previewSize == 'desktop', 'device-tablet': this.$store.state.editorState.previewSize == 'tablet', 'device-mobile': this.$store.state.editorState.previewSize == 'mobile', }"
      >
      <PreviewFrame>
        <draggable
          class="icl-editor__content"
          v-model="pageBlocks"
          group="component"
          handle=".component__drag-handle">
          <div 
            class="icl-editor__block"
            v-for="(block, index) in pageBlocks"
            :key="block.id"
            :class="{'is-selected': editorState.selectedBlock == block.id, 'is-header' : block.category == 'header'} "
            @click="setSelectedBlock(block.id)">
            
            <button
              data-tip="Edit blok ini"
              class="icl-editor__block-options"
              @click="setActiveBlock(block.id, $event)">
              <i class="mdi mdi-edit"></i>
            </button>
            <button class="icl-editor__block-remove" @click.prevent="removeBlock(index)"><i class="mdi mdi-delete"></i></button>
            <div class="icl-editor__block-selection">
            </div>
            <Block :model="block" />
          </div>

          <div class="icl-editor__content--empty" v-if="pageBlocks.length == 0">
            <div class="icl-editor__content--empty-wrap">
              <div class="icl-editor__preview-wrap">
                <h2>Masih kosong nih<br>halamannya</h2>
                <p>Yuk mulai menambahkan block atau mulai dari template</p>
                <div class="icl-editor__buttonwrap">
                  <button class="button is-primary icl-editor__buttonwrap-buttonflex" @click="openBlockTab">
                    <i class="material-icons mr-2">add</i> Pilih Blok
                  </button>
                </div>
                <div class="atau">atau</div>
              </div>
              <div class="start-from-template icl-editor__buttonwrap">
                <button @click="openTemplate" class="button is-info icl-editor__buttonwrap-buttonflex">
                  <span class="material-icons mr-2">format_paint</span> Pilih Template
                </button>
              </div>
            </div>
          </div>

          <div class="icl-editor__content--loading" v-if="this.$store.state.editing && this.$store.state.editorState.loading">
            <svg width="100px"  height="100px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ellipsis" style="background: none;"><!--circle(cx="16",cy="50",r="10")--><circle cx="84" cy="50" r="0" fill="#71c2cc"><animate attributeName="r" values="10;0;0;0;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="84;84;84;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate></circle><circle cx="84" cy="50" r="0.457826" fill="#d8ebf9"><animate attributeName="r" values="0;10;10;10;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="-1s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="-1s"></animate></circle><circle cx="82.4434" cy="50" r="10" fill="#5699d2"><animate attributeName="r" values="0;10;10;10;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="-0.5s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="-0.5s"></animate></circle><circle cx="48.4434" cy="50" r="10" fill="#1d3f72"><animate attributeName="r" values="0;10;10;10;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate></circle><circle cx="16" cy="50" r="9.54217" fill="#71c2cc"><animate attributeName="r" values="0;0;10;10;10" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="16;16;16;50;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2s" repeatCount="indefinite" begin="0s"></animate></circle></svg>
          </div>
        </draggable>
      </PreviewFrame>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable"
import PreviewFrame from "@/components/PreviewFrame"
import Block from "@/components/Block"
import ContactForm from "@/components/ContactForm"

export default {
  name: 'Preview',
  components: {
    PreviewFrame,
    draggable,
    Block,
    ContactForm
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
    project(){
      return this.$store.state.project
    },
    pageBlocks: {
      get() {
        const index = _.findIndex(this.project.pages, i=>i.id == this.$store.state.editing)
        if (index !== -1)
          return this.project.pages[index].blocks
        else
          return []
      },
      set(value) {
        this.$store.dispatch( 'loadTemplate', value )
      }
    }
  },
  mounted(){
    // document.addEventListener('keyup', this.escapeAction, false);
    
  },
  methods: {
    // escapeAction(event){
    //   var key = event.key || event.keyCode;
    //   if ( key == "Escape" ) {
    //     console.log( "escape preseed" );
    //   }
    // },
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

      // return console.log(_.cloneDeep(data));
      // console.log(data);
      return this.$store.dispatch( 'loadTemplate', data );
      // return this.$store.commit('UPDATE_PAGE', data.blocks );
    },
    focusOnPreview(){
      this.mask = false
      this.$store.commit('activeSidebarTab', null)
    },
    openBlockTab(){
      if (! this.$store.state.project.pages.length ) {
        this.$swal({
          title: 'Halaman Kosong',
          text: "Anda belum memiliki halaman, buat satu dan tambahkan block sekarang",
          type: 'warning',
        })

        this.$store.dispatch('setSidebarTab', 'page' );
      }
      this.$blockModal.show()
    },
    setSelectedBlock(id) {
      if ( this.$store.state.editorState.selectedBlock !== id )
        this.$store.commit('SELECTED_BLOCK', id);
      else
        this.$store.commit('SELECTED_BLOCK', null);
    },
    setActiveBlock(id) {
      const sidebar = document.querySelector('.icl-editor__sidebar');

      if ( this.editorState.activeSidebarTab !== 'structure' ) {
        this.$store.commit('activeSidebarTab', 'structure')
      } else {
      }
      this.$store.commit('ACTIVE_BLOCK', id)

      // if ( this.pageBlocks.length === 0 ) {
      //   this.editorState.activeSidebarTab = 'structure'
      // }

      // if ( this.editorState.activeSidebarTab !== 'option' ) {
      //   this.$store.commit('ACTIVE_BLOCK', index)
      //   this.editorState.activeSidebarTab = 'option'
      // } else {
      //   this.$store.commit('ACTIVE_BLOCK', index)
      // }

      sidebar.focus()
      
    },
    removeBlock(index){
      this.$swal({
        title: 'Hapus Blok Ini?',
        text: "Anda tidak dapat mengembalikannya lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus saja!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        // console.log(result);
        if (result.value) {
          this.$store.commit('removeBlock', index)
          // this.editorState.activeBlock = null
          // this.$store.commit('removeBlock', index)
          this.$store.commit('ACTIVE_BLOCK', null)
        }
      })
    },
  }
}
</script>

<style lang="less">
.icl-editor{
  &__preview{
    flex: 1;
    background-color: #fbfbfb;
    border-left: 1px solid #e3e3e3;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-left: 400px;
    transition: .3s ease;
    position: relative;

    .preview-mode &{
      margin-left: 0;
    }

    &-frame{
      flex: 1;
      display: flex;
      flex-direction: column;
      // background-color: white;
      width: 100%;
      transition: .3s ease;
      // padding: 30px;

      &.device-tablet{
        max-width: 768px;
        box-shadow: 0 3px 10px rgba(0,0,0,.1);
      }
      &.device-mobile{
        max-width: 480px;
        box-shadow: 0 3px 10px rgba(0,0,0,.1);
      }
    }

    &-iframe{
      width: 100%;
      height: 100%;
      border: none;
      // box-shadow: 0 3px 20px rgba(0,0,0,.1);
    }
  }

  &__buttonwrap{
    display: flex;
    align-items: center;
    justify-content: center;

    &-buttonflex{
      display: flex !important;
    }
  }
}
</style>