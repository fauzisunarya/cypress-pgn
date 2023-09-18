import IconPickerModal from './IconPickerModal'

const Plugin = {
	install(Vue) {
		this.event = new Vue()

		Vue.component('icon-picker-modal', IconPickerModal)

		Vue.prototype.$iconPicker = {
			show(params) {
				Plugin.event.$emit('showIconPicker', params)
			}
		}
	}
}

export default Plugin