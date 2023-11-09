import Vue from 'vue'
import App from './App.vue'
// import router from './router'
import store from './store'
import UUID from 'vue-uuid'
import VueQuillEditor, { Quill } from 'vue-quill-editor/src'
import Notifications from 'vue-notification'
import VueSweetalert2 from 'vue-sweetalert2'
import VueSlider from 'vue-slider-component'
import IconPickerModal from '@/components/IconPicker'
import MediaPickerModal from '@/components/MediaPicker'
import BlockModal from '@/components/BlockModal'
import TemplateModal from '@/components/TemplateModal'
import LinkBuilder from '@/components/LinkBuilder'
import FormBuilder from '@/components/FormBuilder'
import imagesLoaded from 'vue-images-loaded'
import NProgress from 'vue-nprogress'

import 'sweetalert2/dist/sweetalert2.min.css';

const Clipboard = Quill.import('modules/clipboard')
const Delta = Quill.import('delta');

const options = {
  confirmButtonColor: '#41b882',
  cancelButtonColor: '#ff7674'
}
 

Vue.config.productionTip = false
Vue.prototype.global = window;

Vue.use(UUID);
Vue.use(Notifications)
Vue.use(VueSweetalert2, options)
Vue.use(IconPickerModal)
Vue.use(MediaPickerModal)
Vue.use(BlockModal)
Vue.use(TemplateModal)
Vue.use(LinkBuilder)
Vue.use(FormBuilder)
Vue.use(NProgress)

Vue.component('VueSlider', VueSlider)
Vue.directive('images-loaded', imagesLoaded)

Vue.use(VueQuillEditor, {})

const nprogress = new NProgress()

new Vue({
  nprogress,
  // router,
  store,
  computed: {
    config(){
      return window.builder_vars
    }
  },
  render: h => h(App)
}).$mount('#app')
