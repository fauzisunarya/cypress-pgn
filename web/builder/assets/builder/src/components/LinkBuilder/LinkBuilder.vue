<template>
  <transition name="fade">
    <div class="icl-editor__link-builder-modal" v-if="visible">
      <div class="icl-editor__link-builder-modal-window">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <span class="nav-link" :class="{'active': linkType == 'whatsapp'}" @click="linkType = 'whatsapp'"><i class="fab fa-whatsapp"></i> Whatsapp</span>
          </li>
          <li class="nav-item">
            <span class="nav-link" :class="{'active': linkType == 'email'}" @click="linkType = 'email'"><i class="fas fa-envelope"></i> Email</span>
          </li>
          <li class="nav-item">
            <span class="nav-link" :class="{'active': linkType == 'telepon'}" @click="linkType = 'telepon'"><i class="fas fa-phone"></i> Telepon</span>
          </li>
        </ul>

        <div v-if="linkType== 'whatsapp'" class="link-type-form">
          <p>Link yang akan mengirimkan pesan kepada nomor tujuan ketika diklik</p>
          <div class="form-group">
            <div class="form-group">
              <label class="form-label">Nomor telepon</label>
              <input type="text" class="form-control" placeholder="+6281XXXXXXXX" v-model="whatsapp.phone">
              <div class="form-help">Nomor telepon harus diawali dengan kode negara, contoh: +6281XXXXXXXX</div>
            </div>
            <div class="form-group">
              <label class="form-label">Pesan Awal</label>
              <textarea class="form-control" placeholder="Pesan Whatsapp" v-model="whatsapp.text"></textarea>
            </div>
          </div>
        </div>

        <div v-if="linkType== 'email'" class="link-type-form">
          <p>Link yang akan mengirimkan email kepada nomor tujuan melalui aplikasi email yang terinstall ketika diklik</p>
          <div class="form-group">
            <div class="form-group">
              <label class="form-label">Alamat Email</label>
              <input type="email" class="form-control" v-model="email.address">
            </div>
            <div class="form-group">
              <label class="form-label">Judul/Subjek Email</label>
              <input type="text" class="form-control" v-model="email.subject">
            </div>
            <div class="form-group">
              <label class="form-label">Pesan Email</label>
              <textarea class="form-control" v-model="email.body"></textarea>
            </div>
          </div>
        </div>

        <div v-if="linkType== 'telepon'" class="link-type-form">
          <p>Link yang akan membuka aplikasi telepon dengan nomor tujuan</p>
          <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" placeholder="081XXXXXXXX" v-model="phone">
          </div>
        </div>

        <div class="link-builder-action text-right">
          <button @click="visible=false" class="btn btn-secondary mr-2">Batal</button>
          <button @click="buildLink" class="btn btn-primary">Buat Link</button>
        </div>

      </div>
    </div>
  </transition>
</template>

<script>
import Modal from './index'
export default {
  name: 'link-builder',
  data() {
    return {
      visible    : false,
      linkType   : 'whatsapp',
      onSelected : {},
      whatsapp : {
        phone: '',
        text: ''
      },
      email: {
        address: '',
        subject: '',
        body: ''
      },
      phone: ''
    }
  },

  methods: {
    show(params) { 
      this.visible = true
      this.whatsapp = {
        phone: this.defaultWAPhone || this.$store.state.project.meta.whatsapp_button || '',
        text: this.defaultText || ''
      }
      this.email = {
        address: this.defaultAddress || '',
        subject: this.defaultSubject || '',
        body: this.defaultBody || ''
      }
      this.phone = this.defaultPhone || ''
      this.onSelected = params.onSelected
    },
    setDefaultData(url){
      this.clearInput();
      if (url.includes('api.whatsapp.com')) {
        var defaultUrl = new URL(url);
        
        this.linkType = 'whatsapp';
        this.defaultWAPhone = defaultUrl.searchParams.get("phone"); 
        this.defaultText = decodeURI(defaultUrl.searchParams.get("text"));
      } else if(url.includes('mailto:')) {
        var defaultUrl = new URL(url);

        this.linkType = 'email';
        this.defaultAddress = url.substring(
            url.lastIndexOf("mailto:") + 7, 
            url.lastIndexOf("?subject")
        );
        this.defaultSubject = defaultUrl.searchParams.get("subject"); 
        this.defaultBody = decodeURI(defaultUrl.searchParams.get("body"));
      } else if(url.includes('tel:')) {
        this.linkType = 'telepon';
        this.defaultPhone = url.substring(
            url.lastIndexOf("tel:") + 4
        );
      }
    },
    clearInput(){
      this.defaultWAPhone = '', this.defaultText = '', this.defaultAddress = '', this.defaultSubject = '', this.defaultBody = '', this.defaultPhone = '';
    },
    buildLink(){
      var link = '';
      switch( this.linkType ) {
        case 'whatsapp':
          link = `https://api.whatsapp.com/send?phone=${this.whatsapp.phone}&text=${encodeURI(this.whatsapp.text)}`;
          break;
        case 'email':
          link = `mailto:${this.email.address}?subject=${this.email.subject}&body=${encodeURI(this.email.body)}`;
          break;
        default:
          link = `tel:${this.phone}`;
          break;
      }

      this.onSelected( link );

      this.visible = false;
    },
    
  },
  beforeMount(){
    Modal.event.$on('showLinkBuilder', (params)=>{
      this.show(params)
    })
    Modal.event.$on('setDefaultData', (url)=>{
      this.setDefaultData(url)
    })
  },
}
</script>

<style lang="less">
  .icl-editor__link-builder-modal{
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background-color: rgba(0,0,0,.8);
    // backdrop-filter: blur(2px);
    &-window{
      max-width: 640px;
      background-color: white;
      box-shadow: 0 10px 30px rgba(0,0,0,.5);
      border-radius: 6px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      overflow: hidden;
      position: absolute;
      left: 15px;
      right: 15px;
      top: 0;
      margin: auto;
      // padding-left: 250px;
    }

    .nav-tabs{
      padding-top: 20px;
      background-color: #fbfbfb;

      .nav-link:first-child{
        margin-left: 20px;
      }
    }
  }

  .link-type-form {
    padding: 20px;
  }
  .form-help{
    color: #666;
    padding-top: 5px;
    font-style: italic;
  }

  .link-builder-action{
    padding: 10px 15px;
    border-top: 1px solid #e3e3e3;
    background-color: #fbfbfb;
  }
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
</style>