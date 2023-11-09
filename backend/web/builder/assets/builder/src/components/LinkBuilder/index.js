import LinkBuilder from './LinkBuilder'

const Plugin = {
  install(Vue) {
    this.event = new Vue()

    Vue.component('link-builder', LinkBuilder)

    Vue.prototype.$linkBuilder = {
      setDefaultData(url){
        Plugin.event.$emit('setDefaultData', url)
      },
      show(params) {
        Plugin.event.$emit('showLinkBuilder', params)
      }
    }
  }
}

export default Plugin