import TemplateModal from './TemplateModal'

const Plugin = {
  install(Vue) {
    this.event = new Vue()

    Vue.component('template-modal', TemplateModal)

    Vue.prototype.$templateModal = {
      show(params) {
        Plugin.event.$emit('showTemplateModal', params)
      }
    }
  }
}

export default Plugin