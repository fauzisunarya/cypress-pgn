import BlockModal from './BlockModal'

const Plugin = {
  install(Vue) {
    this.event = new Vue()

    Vue.component('block-modal', BlockModal)

    Vue.prototype.$blockModal = {
      show(params) {
        Plugin.event.$emit('showBlockModal', params)
      }
    }
  }
}

export default Plugin