import { trans } from 'laravel-vue-i18n'
export default (el, binding) => {
    if (typeof binding.value == 'object') {
        el.textContent = trans(binding.value.text, binding.value.replace)
    } else {
        el.textContent = trans(binding.value)
    }
}
