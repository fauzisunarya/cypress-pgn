import FormBuilder from './FormBuilder'

const Plugin = {
  install(Vue) {
    this.event = new Vue()

    Vue.component('form-builder', FormBuilder)

    Vue.prototype.$FormBuilder = {
      show(params) {
        Plugin.event.$emit('show', params)
      }
    }
  }
}

export default Plugin