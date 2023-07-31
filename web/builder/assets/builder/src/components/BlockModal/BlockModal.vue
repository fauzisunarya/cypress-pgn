<template>
  <transition name="fade">
    <div class="icl-editor__block-modal" v-if="visible">
      <div class="icl-editor__block-modal-window">
        <div class="icl-editor__block-modal-sidebar">
          <div class="search-block">
            <input type="text" @keyup="debounceFilter" placeholder="Cari Blok">
          </div>
          <div class="block-categories">
            <label :class="{'is-active': activeBlockCategory == '*' }" ><input type="radio" v-model="activeBlockCategory" @click="filter('*')" value="*"><span class="label">Semua Blok</span><span class="count">{{this.blocks.length}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'header' }"  v-if="getBlockCount('header')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="header"><span class="label">Header</span><span class="count">{{getBlockCount('header')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'content' }"  v-if="getBlockCount('content')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="content"><span class="label">Konten</span><span class="count">{{getBlockCount('content')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'call-to-action' }"  v-if="getBlockCount('call-to-action')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="call-to-action"><span class="label">Call to action</span><span class="count">{{getBlockCount('call-to-action')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'feature' }"  v-if="getBlockCount('feature')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="feature"><span class="label">Fitur</span><span class="count">{{getBlockCount('feature')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'slider' }"  v-if="getBlockCount('slider')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="slider"><span class="label">Slider</span><span class="count">{{getBlockCount('slider')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'contact' }"  v-if="getBlockCount('contact')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="contact"><span class="label">Kontak</span><span class="count">{{getBlockCount('contact')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'form' }"  v-if="getBlockCount('form')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="form"><span class="label">Formulir</span><span class="count">{{getBlockCount('form')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'pricing' }"  v-if="getBlockCount('pricing')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="pricing"><span class="label">Tabel Harga</span><span class="count">{{getBlockCount('pricing')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'team' }"  v-if="getBlockCount('team')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="team"><span class="label">Tim</span><span class="count">{{getBlockCount('team')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'testimonial' }"  v-if="getBlockCount('testimonial')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="testimonial"><span class="label">Testimonial</span><span class="count">{{getBlockCount('testimonial')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'product' }"  v-if="getBlockCount('product')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="product"><span class="label">Produk</span><span class="count">{{getBlockCount('product')}}</span></label>
            <label :class="{'is-active': activeBlockCategory == 'compare-product' }"  v-if="getBlockCount('compare-product')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="compare-product"><span class="label">Komparasi Produk</span><span class="count">{{getBlockCount('compare-product')}}</span></label>

            <label :class="{'is-active': activeBlockCategory == 'footer' }"  v-if="getBlockCount('footer')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="footer"><span class="label">Footer</span><span class="count">{{getBlockCount('footer')}}</span></label>

            <!-- <label :class="{'is-active': activeBlockCategory == 'post' }"  v-if="getBlockCount('post') && $store.state.project.quota > 1"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="post"><span class="label">Posting/Artikel</span><span class="count">{{getBlockCount('post')}}</span></label> -->

            <!-- <label :class="{'is-active': activeBlockCategory == 'catalog' }"  v-if="getBlockCount('catalog') && $store.state.project.quota > 1"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="catalog"><span class="label">Catalog</span><span class="count">{{getBlockCount('catalog')}}</span></label> -->

            <label :class="{'is-active': activeBlockCategory == 'sosmed' }"  v-if="getBlockCount('sosmed')"><input type="radio" v-model="activeBlockCategory" @click="filter('filterByCategory')" value="sosmed"><span class="label">Social Media</span><span class="count">{{getBlockCount('sosmed')}}</span></label>
          </div>
        </div>
        <div class="icl-editor__block-modal-header">
            <div class="icl-editor__block-modal-tab">
              <span @click.prevent="setBlockModalTab('block')" :class="{'is-active': blockModalTab == 'block' }"><i class="mdi mdi-layers"></i> Blok</span>
              <span @click.prevent="setBlockModalTab('saved')" :class="{'is-active': blockModalTab == 'saved' }"><i class="mdi mdi-bookmark"></i> Blok disimpan</span>
            </div>

            <button @click="closeBlockModal"><i class="mdi mdi-close"></i></button>
        </div>
        <div class="icl-editor__block-modal-content">
          <div
            class="block-content"
            :class="{'is-active': blockModalTab == 'block'}">
            <isotope
              ref="blocks"
              class="masonry"
              :options='options'
              :list="blocks"
              @filter="filterOption=arguments[0]">

              <div
                :class="`masonry-item ${block.category}`"
                v-for="block in blocks"
                :key="block.blockID">
                <figure
                  class="icl-editor-design-block"
                  @click="addBlock(block)">
                  <div class="image-screenshot" :style="{paddingBottom: block.screenshot_size[1]/block.screenshot_size[0]*100 + '%'}">
                    <img v-if="block.screenshot" :src="block.screenshot" :alt="block.title">
                  </div>
                  <figcaption>{{block.label || block.title}}</figcaption>
                </figure>
              </div>
            </isotope>

          </div>

          <div
            class="block-content"
            :class="{'is-active': blockModalTab == 'saved'}">
            <ul class="template-list" v-if="savedBlocks.length">
              <li v-for="(block) in savedBlocks" :key="block.id">
                <span class="name">{{block.name}}</span>
                <button class="add_block" @click="addBlock(block.blocks)"><i class="mdi mdi-add_circle"></i></button>
                <button class="delete_block" @click.stop.prevent="deleteSavedBlock(block.id)"><i class="mdi mdi-delete"></i></button>
              </li>
            </ul>
            <div v-else class="empty-saved-block">
              <h2>Blok Kosong</h2>
              <p>Anda belum menyimpan blok buatan sendiri</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import qs from 'qs';
import Modal from './index'
import Axios from 'axios'
import isotope from 'vueisotope'
import imagesLoaded from 'vue-images-loaded'
import debounce from 'lodash/debounce'
import countBy from 'lodash/countBy'
import findIndex from 'lodash/findIndex'
import cloneDeep from 'lodash/cloneDeep'
export default {
  name: 'block-modal',
  directives: {
    imagesLoaded
  },
  components: {
    isotope,
  },
  data() {
    return {

      visible             : false,
      blockModalTab       : 'block',
      activeBlockCategory : "*",
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
          filterByCategory: (el) => {
            return el.category === this.activeBlockCategory;
          },
          filterByText: (el) => {
            return el.title.toLowerCase().includes(this.filterText.toLowerCase());
          }
        },
      },
    }
  },
  computed:{
    editorState(){
      return this.$store.state.editorState
    },
    savedBlocks(){
      return this.$store.state.project.user_blocks
    },
    blocks(){
      return this.$store.state.editorState.blocks
    },
  },
  watch: {
    blockModalTab( after ) {
      if ( 'block' == after ) {
        // console.log( 'layout' );
        setTimeout( () =>{
          this.layout()
        },300 )
      }
    }
  },
  methods: {
    setBlockModalTab(tab){
      this.blockModalTab = tab
    },
    debounceFilter: debounce(function(event){
      this.filterText = event.target.value;
      this.filter('filterByText');
    },150),
    show() {
      this.visible = true;
      setTimeout(()=>{
        if ( this.activeBlockCategory !== "*" )
          this.filter('filterByCategory')
        else
          this.filter('*')
      }, 300);
    },
    closeBlockModal(){
      this.visible= false;
      // this.activeBlockCategory = '*'
    },
    getBlockCount(category){
      const index = countBy(this.blocks,i=>i.category == category);
      return index.true || ''
    },
    layout () {
      this.$refs.blocks && this.$refs.blocks.layout('masonry');
      if ( this.activeBlockCategory !== "*" )
        this.filter('filterByCategory')
      else
        this.filter('*')
    },
    filter(key) {
      this.blockModalTab = 'block';
      if ( key == "*" ) this.activeBlockCategory = "*"
      this.$refs.blocks && this.$refs.blocks.filter(key);
    },
    getCurrentPageBlocks(){
      const id = this.$store.state.editing;
      const index = findIndex( this.$store.state.project.pages, page => page.id == id )
      return this.$store.state.project.pages[index].blocks;
    },
    currentPageHasPostIndex(){
      const blocks       = this.getCurrentPageBlocks();
      const blockIndexId = ["post-01","post-02","post-03","post-06","post-07","post-08","post-09","post-10","post-11","post-12"];

      let filtered = blocks.filter( block => {
        return blockIndexId.includes(block.blockID)
      } )

      return filtered.length > 0
    },
    currentPageHasPostSingle(){
      const blocks       = this.getCurrentPageBlocks();
      const blockSingleId = ["post-04","post-05"]

      let filtered = blocks.filter( block => {
        return blockSingleId.includes(block.blockID)
      } )
      return filtered.length > 0
    },
    addBlock(block){
      const blockIndexId = ["post-01","post-02","post-03","post-06","post-07","post-08","post-09","post-10","post-11","post-12"];
      const blockSingleId = ["post-04","post-05"]

      // Check if current page is blog index
      if ( this.$store.state.editing == this.$store.state.project.post_index ) {
        // This is post index

        // Check if current block is single block.
        if ( blockSingleId.includes(block.blockID) )
          return this.$swal({
            title: 'Perhatian!',
            text: "Halaman ini diatur sebagai daftar posting. Blok post detail tidak dimasukkan kedalam halaman ini",
            type: 'warning'
          })

        // Check if current page has post index block
        if ( blockIndexId.includes(block.blockID) && this.currentPageHasPostIndex() ) {
          return this.$swal({
            title: 'Perhatian!',
            text: "Halaman sudah memiliki blok Daftar Posting/Artikel. Blok ini hanya boleh ditambahkan 1 kali",
            type: 'warning'
          })
        }

      } else if ( this.$store.state.editing == this.$store.state.project.post_single ) {
        // This is single post

        // Check if current block is index block
        if ( blockIndexId.includes(block.blockID) && this.currentPageHasPostIndex() ) {
          return this.$swal({
            title: 'Perhatian!',
            text: "Halaman sudah memiliki blok Daftar Posting/Artikel. Blok ini hanya boleh ditambahkan 1 kali",
            type: 'warning'
          })
        }

        // Check if current page already has single block
        // return this.currentPageHasPostSingle();
        if ( blockSingleId.includes(block.blockID) && this.currentPageHasPostSingle() ) {
          return this.$swal({
            title: 'Perhatian!',
            text: "Halaman sudah memiliki blok detail Posting/Artikel. Blok ini hanya boleh ditambahkan 1 kali",
            type: 'warning'
          })
        }
      } else {

        // Check if the block is sigle post block
        if ( blockSingleId.includes(block.blockID) )
          return this.$swal({
            title: 'Perhatian!',
            text: "Blok post detail hanya bisa dimasukkan ke dalam halaman yang diatur sebagai post detail",
            type: 'warning'
          })

      }

      var cloned = cloneDeep(block)

      cloned.id = this.$uuid.v1()
      cloned.name = block.title

      this.editorState.activeBlock = cloned.id
      this.$store.dispatch('addPageBlock', cloned)
      this.visible = false
    },
    deleteSavedBlock(id) {
      const options = {
        method          : 'DELETE',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        url             : this.$root.config.api+'/blocks/user/'+id,
        withCredentials : true
      };

      this.$swal({
        title: 'Hapus block ini?',
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
            .then( () => {
              this.$store.commit('REMOVE_USER_BLOCK',id);

              this.$notify({
                group : 'builder',
                title : 'Berhasil',
                text  : `Blok berhasil dihapus`,
                type  : 'success'
              });

            })
            .catch(error=>{
              this.$notify({
                group : 'builder',
                title : 'Gagal',
                text  : `Gagal menghapus blok. \n kode error: ${error}`,
                type  : 'success'
              });
            })
        }
      })

    },
    closeByEsc(event) {
      var key = event.key || event.keyCode;
      if ( key == "Escape" ) {
        this.closeBlockModal();
      }
    }
  },
  mounted(){
    this.filter('filterByCategory');

    document.addEventListener( 'keyup', this.closeByEsc, false );
  },
  beforeMount(){
    Modal.event.$on('showBlockModal', (params)=>{
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

.icl-editor__block-modal{
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 999;
  background-color: rgba(0,0,0,.8);
  // backdrop-filter: blur(2px);
  &-window{
    max-width: 80%;
    height: 90vh;
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
    padding-left: 250px;
  }
  &-sidebar{
    width: 250px;
    background-color: #333;
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    color: white;
    overflow: auto;

    .search-block{
      padding: 15px;
      input {
        width: 100%;
        border-radius: 3px;
        border: none;
        padding: 5px 10px;
      }
    }


    .block-categories{
      // padding-top: 30px;
    }

    label{
      display: flex;
      justify-content: space-between;
      cursor: pointer;
      font-size: 14px;
      padding: 10px 15px;
      margin-bottom: 0;
      transition: .3s ease;
      &:hover{
        background-color: rgba(white, .2);
      }
      &.is-active{
        background-color: var(--primary);
        color: white;
        font-weight: 700;
      }
      input{
        display: none;
      }
      .count{
        background-color: rgba(0,0,0,.3);
        border-radius: 4px;
        padding: 2px 4px;
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
    bottom: 0;
    top: 54px;
    padding: 5px;
    background-color: #e9ecf1
  }
}
.masonry{
  width: 100%;
  &-item{
    width: 50%;
    padding: 10px;
    cursor: pointer;

    // &.header{
    //   width: 100%;
    // }

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
  transition: opacity .1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.saved-block{
  display: flex;
  flex-wrap: wrap;
}
.empty-saved-block{
  position: absolute;
  width: 100%;
  left: 0;
  top: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;

}
.block-content{
  display: none;
  background-color: #e9ecf1;
  width: 100%;

  &.is-active{ display: block }
}
.template-list{
  // width: 100%;
}
.image-screenshot{
  position: relative;

  img{
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: white;
    background-image: url('data:image/gif;base64,R0lGODlhEAALAPQAAP///wCQ/9ru/tDq/ur1/gaS/gCQ/y6j/oLI/mC5/rrg/iKe/kqw/orL/mS7/r7i/iag/gSR/k6x/ubz/tjt/vT5/jio/tzv/vL4/rbe/qDV/srn/u73/gAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCwAAACwAAAAAEAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQJCwAAACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQJCwAAACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHTuBNOmcJVCyoUlk7CEAAh+QQJCwAAACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V55zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAkLAAAALAAAAAAQAAsAAAUyICCOZGme1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkECQsAAAAsAAAAABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2y6C+4FIIACH5BAkLAAAALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2isSacYUc+l4tADQGQ1mvpBAAIfkECQsAAAAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7AAAAAAAAAAAA');
    background-repeat: no-repeat;
    background-position: center;
  }
}
</style>