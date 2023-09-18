<template>
<transition name="fade">
  <div class="icl-media-picker" v-if="visible">
    <div class="icl-media-picker__window">
      <header class="icl-media-picker__header">
        <h2>{{title}}</h2>
        <button @click="onClose"><i class="mdi mdi-close"></i></button>
      </header>
      
      <ul class="nav nav-tabs">
        <li class="nav-item" @click="activeTab='upload'">
          <a class="nav-link" :class="{'active': activeTab == 'upload'}">Upload</a>
        </li>
        <li class="nav-item" @click="activeTab='url'">
          <a class="nav-link" :class="{'active': activeTab == 'url'}">URL</a>
        </li>
        <li class="nav-item" @click="activeTab='media'">
          <a class="nav-link" :class="{'active': activeTab == 'media'}">Media</a>
        </li>
        <!-- <li class="nav-item" @click="activeTab='unsplash'">
          <a class="nav-link" :class="{'active': activeTab == 'unsplash'}">Unsplash</a>
        </li> -->
        <li class="nav-item" @click="activeTab='asset-gambar'">
          <a class="nav-link" :class="{'active': activeTab == 'asset-gambar'}">Asset Gambar</a>
        </li>

        <li style="margin-left: auto; display: flex" class="px-3" v-if="['media', 'asset-gambar'].includes(activeTab)">
          <input type="text" v-model="filterParams.search" placeholder="search..." class="form-control mr-3">
          <select v-model="filterParams.category" class="form-control">
            <option value=''>Semua kategori</option>
            <option v-for="(val, i) in filterParams.listCategory" :key="i" :value="val" >{{val}}</option>
          </select>
        </li>
      </ul>

      <div class="icl-media-picker__media icl-media-picker__unsplash" v-if="activeTab == 'unsplash'">
        <div class="unsplash-search-wrap">
          <div class="unsplash-search-content">
            <div class="unsplash-search">
              <input type="text" v-model="unsplashQuery" @keyup="debounceSearch" placeholder="Cari gambar">
            </div>
            
            <div class="unsplash-grid">
              <div class="unsplash-image" v-for="image in unsplashResult" :key="image.urls.thumb">
                <label @click="setSelectedUnsplashImage(image)">
                  <input type="radio" :value="image.urls.regular" v-model="unsplashSelected" />
                  <div class="image-frame">
                    <img :src="image.urls.thumb" :alt="image.alt_description">
                  </div>
                </label>
              </div>

              <div class="unsplash-empty" v-show="isLoading">
                <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
              </div>

              <div class="unsplash-empty" v-show="!isLoading && !unsplashResult.length && unsplashQuery">
                <h2>Tidak ada gambar</h2>
                <p>Gambar tidak ditemukan untuk kata kunci tersebut</p>
              </div>

              <div class="unsplash-empty" v-show="!unsplashQuery">
                <svg class="_2m4hn" version="1.1" viewBox="0 0 32 32" width="32" height="32" aria-labelledby="unsplash-home" aria-hidden="false"><title id="unsplash-home">Unsplash Home</title><path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path></svg>
                <h2>Unsplash</h2>
                <p>Ketik kata kunci untuk menelurusi gambar dari Unsplash.com</p>
              </div>
            </div>

            <div v-if="unsplashResult.length" class="unsplash-pagination">
              <div class="left">
                {{this.unsplashPage}}/{{this.unsplashPageTotal}}
              </div>
              <span class="right">
                <span class="btn-group">
                  <button class="btn btn-outline-secondary" @click="prevSearch">Sebelumnya</button>
                  <button class="btn btn-outline-secondary" @click="nextSearch">Selanjutnya</button>
                </span>
              </span>
            </div>
          </div>
          <div class="unsplash-search-preview">
            <div class="unsplash-search-preview-wrap" v-if="unsplashSelected">
              <div class="unsplash-image-preview">
                <img class="unsplash-image-preview" :src="selectedUnsplashImage.urls.small">
              </div>
              <div class="unsplash-image-setting">
                <div class="image-info" v-if="selectedUnsplashImage">
                  <a :href="selectedUnsplashImage.user.links.html" target="_blank" class="image-author" :title="`Kunjungi profile Fotografer`">
                    <img :src="selectedUnsplashImage.user.profile_image.small" :alt="selectedUnsplashImage.user.name">
                    <div>
                      <span>Fotografer</span>
                      <strong>{{selectedUnsplashImage.user.name}}</strong>
                    </div>
                    <i class="mdi mdi-launch"></i>
                  </a>
                </div>
                <div class="row">
                  <div class="form-group col">
                    <label class="form-label"><strong>Lebar</strong></label>
                    <div class="input-group">
                      <input type="text" placeholder="1080" v-model="width" class="form-control">
                      <div class="input-group-append">
                        <span class="input-group-text">px</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col">
                    <label class="form-label"><strong>Tinggi</strong></label>
                    <div class="input-group">
                      <input type="text" placeholder="auto" v-model="height" class="form-control">
                      <div class="input-group-append">
                        <span class="input-group-text">px</span>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <button class="btn btn-primary btn-block" @click="selectMedia">Pilih gambar</button>
              </div>
              
            </div>
            <div v-else class="d-flex align-items-center">
              Pilih gambar untuk preview
            </div>
          </div>
        </div>
      </div>

      <div class="icl-media-picker__url" v-if="activeTab == 'url'">
        <input type="url" class="form-control" ref="inputURL" placeholder="Insert image URL">
      </div>

      <div class="icl-media-picker__media"  v-if="activeTab == 'upload'">
        <keep-alive>
          <vue-dropzone
            ref="myVueDropzone"
            id="dropzone"
            :options="dropzoneOptions"
            @vdropzone-success="onSuccess"
            @vdropzone-queue-complete="onCompleted"
            :useCustomSlot=true>
            <div class="dropzone-custom-content">
              <i class="mdi mdi-cloud_upload"></i>
              <h3>Drag dan drop untuk meng-<em>upload</em> gambar!</h3>
              <div class="subtitle">...atau klik untuk memilih gambar dari komputer anda.</div>
            </div>
          </vue-dropzone>
        </keep-alive>
      </div>

      <div class="icl-media-picker__media"  v-if="activeTab == 'media'">
        <div class="icl-media-picker__media-grid" v-if="computedMedia.length">
          <label class="media-item" v-for="image in computedMedia" :key="image.filename">
            <input v-if="multiple" type="checkbox" :value="image.uri"  v-model="selected" />
            <input v-else type="radio" :value="image.uri" v-model="selected[0]" />
            <span class="media-item__obj">
              <img :src="image.thumbnail" :alt="image.filename" :key="image.filename" />
            </span>
          </label>
        </div>
        <div v-else class="media-empty" style="flex-direction: column;">
          <h2>Media Kosong</h2>
          <p>Anda belum mengupload gambar</p>
        </div>
      </div>

      <div class="icl-media-picker__media"  v-if="activeTab == 'asset-gambar'">
        <div class="icl-media-picker__media-grid" v-if="computedImageAsset.length">
          <label class="media-item" v-for="(image, index) in computedImageAsset" :key="image.filename + index">
            <input v-if="multiple" type="checkbox" :value="image.uri"  v-model="selected" />
            <input v-else type="radio" :value="image.uri" v-model="selected[0]" />
            <span class="media-item__obj">
              <img :src="image.thumbnail" :alt="image.filename" :key="image.filename" />
            </span>
          </label>
        </div>
        <div v-else class="media-empty" style="flex-direction: column;">
          <h2>Media Kosong</h2>
        </div>
      </div>

      <div class="icl-media-picker__action" v-if="activeTab == 'url'">
        <button @click="selectMedia" class="btn btn-primary">Masukkan gambar</button>
      </div>

      <div class="icl-media-picker__action" v-if="activeTab == 'media' || activeTab == 'upload' || activeTab == 'asset-gambar'">
        <button @click="selectMedia" class="btn btn-primary">Gunakan gambar ini</button>
      </div>
    </div>
  </div>
</transition>
</template>

<script>
import debounce from 'lodash/debounce'
import Modal from './index'
import Axios from 'axios'
import vue2Dropzone from 'vue2-dropzone'

export default {
  name: 'icl-media-picker',
  components: {
    vueDropzone: vue2Dropzone
  },
  data(){
    return {
      unsplashQuery: "",
      unsplashResult: [],
      unsplashSelected: null,
      unsplashSelectedThumb: null,
      unsplashSelectedId: null,
      unsplashPageTotal: 1,
      unsplashPage: 1,
      visible: false,
      multiple: false,
      selected: [],
      filterParams:{
        search  : '',
        category: '',
        listCategory : []
      },
      media: [],
      imageAsset: [],
      activeTab: 'media',
      dropzoneOptions: {
        paramName: 'image',
        url: this.$root.config.api +'/upload',
        thumbnailWidth: 200,
        thumbnailHeight: 200,
        maxFilesize: 2,
        acceptedFiles: 'image/*'
      },
      uploading: [],
      onSelected: {},
      width: 1080,
      height: '',
      isLoading: false,
      selectedUnsplashImage: null,
    }
  },
  methods: {
    debounceSearch: debounce(function(event){
      this.searchImage(event.target.value, this.unsplashPage )
    },300),
    searchImage(query, page){
      this.isLoading = true;
      Axios.get('https://api.unsplash.com/search/photos/?client_id=45a3885732bac395eff8ec480aab5cd60becd471d9fea7f92cf05ac3ce54d0db',{
        params: {
          query,
          page,
          per_page: 12
        }
      })
        .then( response => {
          this.isLoading = false;
          this.unsplashResult = response.data.results
          this.unsplashPageTotal = response.data.total_pages
        } )
    },
    filterMedia(val){
      let passSearch = true;
      let passCategory = true;
      let {search, category} = this.filterParams;
      if (search !== '' && (val.title.toLowerCase().includes(search.toLowerCase()) || val.description.toLowerCase().includes(search.toLowerCase())) ) {
        passSearch = true;
      }
      else if(search == ''){
        passSearch = true;
      }
      else{
        passSearch = false;
      }

      if (category !== '' && val.category.includes(category)) {
        passCategory = true;
      }
      else if(category == ''){
        passCategory = true;
      }
      else{
        passCategory = false;
      }

      return passSearch && passCategory;
    },
    setSelectedUnsplashImage(image){
      this.unsplashSelectedId = image.id;
      this.selectedUnsplashImage = image;
    },
    nextSearch(){
      if ( this.unsplashPage == this.unsplashPageTotal )
        return

      this.unsplashPage++
      this.searchImage(this.unsplashQuery, this.unsplashPage)
    },
    prevSearch(){
      if ( this.unsplashPage == 1 )
        return
      this.unsplashPage--
      this.searchImage(this.unsplashQuery, this.unsplashPage)
    },
    getMedia(){
      Axios.get(this.$root.config.api+"/media")
        .then( response => {
          if ( response.data.status )
            this.media = response.data.content;
            this.filterParams.listCategory = response.data.category;
        })
        .catch( error=>console.log(error) )
    },
    getImageAsset(){
      Axios.get(this.$root.config.api+"/image-asset")
        .then( response => {
          if ( response.data.status )
            this.imageAsset = response.data.content
        })
        .catch( error=>console.log(error) )
    },
    selectMedia(){
      if ( typeof this.onSelected === 'function' ) {

        if ( this.activeTab == 'url' ) {
          if ( this.multiple ) {
            this.selected.push( this.$refs.inputURL.value )
            this.onSelected(this.selected);
            return this.onClose();
          }
          this.onSelected( [this.$refs.inputURL.value] )
          return this.onClose();
        }

        if ( this.activeTab == 'unsplash' ) {

          let unsplashImageURI = this.unsplashSelected.replace('&w=1080', `&w=${parseInt(this.width) || 1080}`);

          if ( parseInt( this.height ) ) {
            unsplashImageURI = unsplashImageURI.replace( '&w=', `&h=${parseInt(this.height)}&w=`);
            unsplashImageURI = unsplashImageURI.replace( 'fit=max', 'fit=crop' );
          }

          Axios.get(`https://api.unsplash.com/photos/${this.unsplashSelectedId}/download?client_id=45a3885732bac395eff8ec480aab5cd60becd471d9fea7f92cf05ac3ce54d0db`)
            .then( response => {
              console.log( response.data )
            } )

          if ( this.multiple ) {
            this.selected.push( unsplashImageURI )
            this.onSelected(this.selected)
            return this.onClose();
          }
          this.onSelected( [ unsplashImageURI ] )
          return this.onClose();
        }

        if ( this.multiple ) {
          this.onSelected(this.selected)
          return this.onClose();
        }

        this.onSelected([this.selected[0]])
        return this.onClose();
      }
    },
    onCompleted(){
      this.getMedia();
      this.getImageAsset();
      // this.activeTab = "media"
    },
    onSuccess(file, response){
      if (this.multiple)
        this.selected.push(response.content.uri)
      else
        this.selected = [response.content.uri]
    },
    onClose(){
      this.visible = false
      this.selected = []
    },
    show(params){
      this.visible = true
      this.title = params.title
      this.selected = params.selected
      this.multiple = params.multiple
      this.onSelected = params.onSelected
    },
    closeByEsc(event) {
      var key = event.key || event.keyCode;
      if ( key == "Escape" ) {
        this.onClose();
      }
    },
  },
  computed: {
    computedMedia() {
      return this.media.filter(this.filterMedia);
    },
    computedImageAsset() {
      return this.imageAsset.filter(this.filterMedia);
    }
  },
  mounted(){
    document.addEventListener( 'keyup', this.closeByEsc, false );
    this.getMedia();
    this.getImageAsset();
  },
  beforeMount(){
    Modal.event.$on('showMediaPicker', (params) => {
      this.show(params)
    })
  }
}
</script>

<style lang="less">
.icl-media-picker{
  position: fixed;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,.7);
  left: 0;
  top: 0;
  z-index: 9999;
  overflow: auto;
  &__window{
    max-width: 1200px;
    background-color: white;
    box-shadow: 0 5px 15px rgba(0,0,0,.3);
    position: absolute;
    top: 0;
    left: 20px;
    right: 20px;
    margin: auto;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    transition: .3s ease;
  }
  &__header{
    padding: 15px;
    background-color: white;
    position: relative;
    // border-bottom: 1px solid #e3e3e3;
    h2{
      font-size: 18px;
      font-weight: 700;
      margin: 0;
      line-height: 1;
    }
    button{
      position: absolute;
      top:10px;
      right: 10px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: darken(red,10%);
      border: none;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 15px;
    }
  }
  .nav-tabs{
    margin-bottom: 0!important;
    .nav-link{
      border-bottom: none;
      cursor: pointer;
      font-weight: 700;
      font-size: 14px
    }
    .nav-link.active{
      background-color:#f1f3f5;
      color: var(--primary);
    }
  }
  .dropzone{
    .dz-message {
      text-align: center;
      margin: 5em 0;
    }
  }
  .dropzone-custom-content{
    i{
      font-size: 48px;
      color: #3273dc;
    }
    h3{
      font-size: 24px;
      margin-bottom: 5px;
    }
    div{
      font-size: 16px;
    }
  }
  &__url{
    background-color: #f1f3f5;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  &__unsplash {

    .unsplash-search{
      width: 100%;
      padding: 10px 20px;
      border: none;
      border-bottom: 1px solid #e3e3e3;
      text-align: center;
      background: #fbfbfb;
      input{
        width: 100%;
        text-align: center;
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #e3e3e3;
      }
    }

    .unsplash-grid{
      display: flex;
      align-items: flex-start;
      flex-wrap: wrap;
      padding: 10px;
      overflow: auto;
      transition: .3s ease;
      max-height: 100%;

      .unsplash-image{
        width: 25%;
        padding: 10px;

        label{
          display: block;
          margin: 0;
        }
        input{
          display: none;

          &:checked + .image-frame{
            box-shadow: 0 0 0 3px blue;
          }
        }

        .image-frame{
          position: relative;
          padding-bottom: 90%;
          cursor: pointer;
          img{
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: block;
            object-fit: cover;
          }
        }

      }
    }  
  }
  &__media{
    height: 70vh;
    display: flex;
    flex-direction: column;

    > div { flex: 1; }

    &-grid {
      display: flex;
      flex: 1;
      flex-wrap: wrap;
      padding: 15px;
      background-color: #f1f3f5;
      overflow: auto;
    }

    .media-item{
      padding: 5px;
      width: 20%;
      cursor: pointer;

      input{
        display: none;
      }
      input:checked + .media-item__obj{
        box-shadow: 0 0 0 2px green;
        &:before{
          opacity: 1;
        }
      }
      .media-item__obj{
        display: block;
        position: relative;
        padding-bottom: 100%;
        width: 100%;
        transition: .3s ease;
        &:before{
          content:"check";
          font-family: "Material Icons";
          position: absolute;
          bottom: 5px;
          right: 5px;
          z-index: 1;
          font-size: 24px;
          width: 32px;
          height: 32px;
          border-radius: 50%;
          background-color: #40c057;
          color: white;
          display: flex;
          align-items: center;
          justify-content: center;
          box-shadow: 0 5px 10px rgba(0,0,0,.5);
          border: 2px solid white;
          transition: opacity .1s ease;
          opacity: 0;
        }
      }
      img{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }
  &__action{
    padding: 15px;
    text-align: right;
  }
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.vue-dropzone{
  width: 100%;
  border: none;
  background-color: transparent;
}

@color_1: rgba(0, 0, 0, .9);
@color_2: white;
@color_3: #777;
@color_4: #ccc;
@color_5: #fff;
@font_family_1: Arial, sans-serif;
@background_color_1: rgba(255, 255, 255, .8);
@background_color_2: rgba(255, 255, 255, .4);
@background_color_3: #f6f6f6;
@background_color_4: rgba(33, 150, 243, .8);
@background_color_5: transparent;

@-webkit-keyframes passing-through {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30%,70% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
  100% {
    opacity: 0;
    -webkit-transform: translateY(-40px);
    -moz-transform: translateY(-40px);
    -ms-transform: translateY(-40px);
    -o-transform: translateY(-40px);
    transform: translateY(-40px);
  }
}
@-moz-keyframes passing-through {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30%,70% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
  100% {
    opacity: 0;
    -webkit-transform: translateY(-40px);
    -moz-transform: translateY(-40px);
    -ms-transform: translateY(-40px);
    -o-transform: translateY(-40px);
    transform: translateY(-40px);
  }
}
@keyframes passing-through {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30%,70% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
  100% {
    opacity: 0;
    -webkit-transform: translateY(-40px);
    -moz-transform: translateY(-40px);
    -ms-transform: translateY(-40px);
    -o-transform: translateY(-40px);
    transform: translateY(-40px);
  }
}
@-webkit-keyframes slide-in {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
}
@-moz-keyframes slide-in {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
}
@keyframes slide-in {
  0% {
    opacity: 0;
    -webkit-transform: translateY(40px);
    -moz-transform: translateY(40px);
    -ms-transform: translateY(40px);
    -o-transform: translateY(40px);
    transform: translateY(40px);
  }
  30% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
    transform: translateY(0);
  }
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
  10% {
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
    -ms-transform: scale(1.1);
    -o-transform: scale(1.1);
    transform: scale(1.1);
  }
  20% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
}
@-moz-keyframes pulse {
  0% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
  10% {
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
    -ms-transform: scale(1.1);
    -o-transform: scale(1.1);
    transform: scale(1.1);
  }
  20% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
}
@keyframes pulse {
  0% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
  10% {
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
    -ms-transform: scale(1.1);
    -o-transform: scale(1.1);
    transform: scale(1.1);
  }
  20% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
  }
}
.dropzone {
  box-sizing: border-box;
  min-height: 150px;

  * {
    box-sizing: border-box;
  }
  .dz-message {
    position: relative;
    top: 50%;
    margin: 0!important;
    transform: translateY(-50%);
  }
  .dz-preview {
    position: relative;
    display: inline-block;
    width: 25%;
    width: calc(20% - 10px);
    vertical-align: top;
    min-height: 100px;
    margin: 5px;

    &:hover {
      z-index: 1000;
      .dz-details {
        opacity: 1;
        opacity: 1;
      }
      .dz-image {
        img {
          -webkit-transform: scale(1.05, 1.05);
          -moz-transform: scale(1.05, 1.05);
          -ms-transform: scale(1.05, 1.05);
          -o-transform: scale(1.05, 1.05);
          transform: scale(1.05, 1.05);
          -webkit-filter: blur(8px);
          filter: blur(8px);
        }
      }
    }
    .dz-remove {
      font-size: 14px;
      text-align: center;
      display: block;
      cursor: pointer;
      border: none;
      &:hover {
        text-decoration: underline;
      }
    }
    .dz-details {
      z-index: 20;
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
      font-size: 13px;
      min-width: 100%;
      max-width: 100%;
      padding: 2em 1em;
      text-align: center;
      color: @color_1;
      line-height: 150%;
      .dz-size {
        margin-bottom: 1em;
        font-size: 16px;
        span {
          background-color: @background_color_2;
          padding: 0 .4em;
          border-radius: 3px;
        }
      }
      .dz-filename {
        white-space: nowrap;
        &:hover {
          span {
            border: 1px solid rgba(200, 200, 200, .8);
            background-color: @background_color_1;
          }
        }
        &:not(:hover) {
          overflow: hidden;
          text-overflow: ellipsis;
          span {
            border: 1px solid transparent;
          }
        }
        span {
          background-color: @background_color_2;
          padding: 0 .4em;
          border-radius: 3px;
        }
      }
    }
    .dz-image {
      border-radius: 20px;
      overflow: hidden;
      width: 120px;
      height: 120px;
      position: relative;
      display: block;
      z-index: 10;
      img {
        display: block;
      }
    }
    .dz-success-mark {
      pointer-events: none;
      opacity: 0;
      z-index: 500;
      position: absolute;
      display: block;
      top: 50%;
      left: 50%;
      margin-left: -27px;
      margin-top: -27px;
      svg {
        display: block;
        width: 54px;
        height: 54px;
      }
    }
    .dz-error-mark {
      pointer-events: none;
      opacity: 0;
      z-index: 500;
      position: absolute;
      display: block;
      top: 50%;
      left: 50%;
      margin-left: -27px;
      margin-top: -27px;
      svg {
        display: block;
        width: 54px;
        height: 54px;
      }
    }
    &:not(.dz-processing) {
      .dz-progress {
        -webkit-animation: pulse 6s ease infinite;
        -moz-animation: pulse 6s ease infinite;
        -ms-animation: pulse 6s ease infinite;
        -o-animation: pulse 6s ease infinite;
        animation: pulse 6s ease infinite;
      }
    }
    .dz-progress {
      opacity: 1;
      z-index: 1000;
      pointer-events: none;
      position: absolute;
      height: 16px;
      left: 50%;
      top: 50%;
      margin-top: -8px;
      width: 80px;
      margin-left: -40px;
      background: rgba(255, 255, 255, .9);
      -webkit-transform: scale(1);
      border-radius: 8px;
      overflow: hidden;
      .dz-upload {
        background: #333;
        background: linear-gradient(to bottom, #666, #444);
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 0;
        -webkit-transition: width 300ms ease-in-out;
        -moz-transition: width 300ms ease-in-out;
        -ms-transition: width 300ms ease-in-out;
        -o-transition: width 300ms ease-in-out;
        transition: width 300ms ease-in-out;
      }
    }
    .dz-error-message {
      pointer-events: none;
      z-index: 1000;
      position: absolute;
      display: block;
      display: none;
      opacity: 0;
      -webkit-transition: opacity 0.3s ease;
      -moz-transition: opacity 0.3s ease;
      -ms-transition: opacity 0.3s ease;
      -o-transition: opacity 0.3s ease;
      transition: opacity 0.3s ease;
      border-radius: 8px;
      font-size: 13px;
      top: 130px;
      left: -10px;
      width: 140px;
      background: #be2626;
      background: linear-gradient(to bottom, #be2626, #a92222);
      padding: .5em 1.2em;
      color: @color_2;
      &:after {
        content: '';
        position: absolute;
        top: -6px;
        left: 64px;
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #be2626;
      }
    }
  }
  .dz-preview.dz-file-preview {
    .dz-image {
      border-radius: 20px;
      background: #999;
      background: linear-gradient(to bottom, #eee, #ddd);
    }
    .dz-details {
      opacity: 1;
    }
  }
  .dz-preview.dz-image-preview {
    background: white;
    .dz-details {
      -webkit-transition: opacity 0.2s linear;
      -moz-transition: opacity 0.2s linear;
      -ms-transition: opacity 0.2s linear;
      -o-transition: opacity 0.2s linear;
      transition: opacity 0.2s linear;
    }
  }
  .dz-preview.dz-success {
    .dz-success-mark {
      -webkit-animation: passing-through 3s cubic-bezier(.77, 0, .175, 1);
      -moz-animation: passing-through 3s cubic-bezier(.77, 0, .175, 1);
      -ms-animation: passing-through 3s cubic-bezier(.77, 0, .175, 1);
      -o-animation: passing-through 3s cubic-bezier(.77, 0, .175, 1);
      animation: passing-through 3s cubic-bezier(.77, 0, .175, 1);
    }
  }
  .dz-preview.dz-error {
    .dz-error-mark {
      opacity: 1;
      -webkit-animation: slide-in 3s cubic-bezier(.77, 0, .175, 1);
      -moz-animation: slide-in 3s cubic-bezier(.77, 0, .175, 1);
      -ms-animation: slide-in 3s cubic-bezier(.77, 0, .175, 1);
      -o-animation: slide-in 3s cubic-bezier(.77, 0, .175, 1);
      animation: slide-in 3s cubic-bezier(.77, 0, .175, 1);
    }
    .dz-error-message {
      display: block;
    }
    &:hover {
      .dz-error-message {
        opacity: 1;
        pointer-events: auto;
      }
    }
  }
  .dz-preview.dz-processing {
    .dz-progress {
      opacity: 1;
      -webkit-transition: all 0.2s linear;
      -moz-transition: all 0.2s linear;
      -ms-transition: all 0.2s linear;
      -o-transition: all 0.2s linear;
      transition: all 0.2s linear;
    }
  }
  .dz-preview.dz-complete {
    .dz-progress {
      opacity: 0;
      -webkit-transition: opacity 0.4s ease-in;
      -moz-transition: opacity 0.4s ease-in;
      -ms-transition: opacity 0.4s ease-in;
      -o-transition: opacity 0.4s ease-in;
      transition: opacity 0.4s ease-in;
    }
  }
}
.dropzone.dz-clickable {
  cursor: pointer;
  * {
    cursor: default;
  }
  .dz-message {
    cursor: pointer;
    * {
      cursor: pointer;
    }
  }
}
.dropzone.dz-started {
  .dz-message {
    display: none;
  }
}
.dropzone.dz-drag-hover {
  border-style: solid;
  .dz-message {
    opacity: .5;
  }
}
.vue-dropzone {
  font-family: @font_family_1;
  letter-spacing: .2px;
  color: @color_3;
  transition: .2s linear;
  padding: 5px;
  >i {
    color: @color_4;
  }
  >.dz-preview {
    .dz-image {
      border-radius: 0;
      width: 100%;
      height: 100%;
      img {
        &:not([src]) {
          width: 200px;
          height: 200px;
        }
      }
      &:hover {
        img {
          transform: none;
          -webkit-filter: none;
        }
      }
    }
    .dz-details {
      bottom: 0;
      top: 0;
      color: @color_5;
      background-color: @background_color_4;
      transition: opacity .2s linear;
      text-align: left;
      .dz-filename {
        overflow: hidden;
        span {
          background-color: @background_color_5;
        }
        &:not(:hover) {
          span {
            border: none;
          }
        }
        &:hover {
          span {
            background-color: @background_color_5;
            border: none;
          }
        }
      }
      .dz-size {
        span {
          background-color: @background_color_5;
        }
      }
    }
    .dz-progress {
      .dz-upload {
        background: #ccc;
      }
    }
    .dz-remove {
      position: absolute;
      z-index: 30;
      color: @color_5;
      margin-left: 15px;
      padding: 10px;
      top: inherit;
      bottom: 15px;
      border: 2px #fff solid;
      text-decoration: none;
      text-transform: uppercase;
      font-size: .8rem;
      font-weight: 800;
      letter-spacing: 1.1px;
      opacity: 0;
    }
    &:hover {
      .dz-remove {
        opacity: 1;
      }
    }
    .dz-error-mark {
      margin-left: auto;
      margin-top: auto;
      width: 100%;
      top: 35%;
      left: 0;
      svg {
        margin-left: auto;
        margin-right: auto;
      }
    }
    .dz-success-mark {
      margin-left: auto;
      margin-top: auto;
      width: 100%;
      top: 35%;
      left: 0;
      svg {
        margin-left: auto;
        margin-right: auto;
      }
    }
    .dz-error-message {
      left: 10px;
      right: 10px;
      bottom: 10px;
      text-align: center;
      &:after {
        display: none;
      }
    }
  }
}
.unsplash-pagination{
  padding: 20px;
  border-top: 1px solid #e3e3e3;
  display: flex;
  justify-content: space-between;
  align-items: center;

  .left, .right{
    width: 100%;
  }
  .btn-group{
    flex-shrink: 0
  }
  .right{
    display: flex;
    justify-content: flex-end;
  }

  .btn{
    font-weight: 700;
    display: flex;
    align-items: center;

    i{
      margin: 5px;
    }
  }
}
.max-width{
  display: flex;
  align-items: center;
  padding-right: 15px;
  margin-bottom: 0;
  strong{
    white-space: nowrap;
  }
  input{
    width: 70px!important;
    margin-left: 15px;
    text-align: center;
  }
}
.unsplash-search-wrap{
  display: flex;
  min-height: 320px;
  

  .unsplash-search-content{
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  .unsplash-search-preview{
    width: 450px;
    background: #fbfbfb;
    border-left: 1px solid #e3e3e3;
    padding: 15px;
    overflow: auto;
    display: flex;
    justify-content: center;

    &-wrap{
      flex: 1;
      display: flex;
      flex-direction: column;
    }
  }
  .unsplash-image-preview{
    overflow: auto;
    flex: 1;
    display: flex;
    align-items: center;

    
    img{
      max-width: 100%;
    }
  }
  .unsplash-image-setting{
    border-top: 1px solid #e3e3e3;
    padding-top: 15px;
    input{
      width: 100px;
    }
  }
}
.media-empty{
  min-height: 200px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #fbfbfb;
  border-bottom: 1px solid #e3e3e3;
}
.unsplash-empty{
  width: 100%;
  text-align: center;
  min-height: 200px;
  flex-direction: column;
  display: flex;
  align-items: center;
  justify-content: center;

  svg{
    margin-bottom: 15px;
  }
}
.image-author{
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #666;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e3e3e3;
  &:hover{
    text-decoration: none;
  }
  img{
    margin-right: 15px;
    border-radius: 50%;
  }
  strong{
    color: #333;
  }
  >div{
    flex: 1;
  }
  span{
    font-size: 14px;
    display: block;
  }
  i{
    margin-left: 10px;
  }

}
</style>