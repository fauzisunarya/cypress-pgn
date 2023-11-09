import MediaPickerModal from './MediaPickerModal'

const Plugin = {
  install(Vue) {
    this.event = new Vue()

    Vue.component('media-picker-modal', MediaPickerModal)

    Vue.prototype.$mediaPicker = {
      show(params) {
        Plugin.event.$emit('showMediaPicker', params)
      }
    }
  }
}

export default Plugin