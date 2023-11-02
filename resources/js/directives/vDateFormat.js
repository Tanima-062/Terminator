import dayjs from "dayjs"

export default (el, binding) => {
    let format = binding.arg == 'datetime' ? 'DD.MM.YYYY HH:mm' : 'DD.MM.YYYY'
    let value = binding.value
    if (typeof value == 'object') {
        format = value.format
        value = value.date
    }
    el.textContent = dayjs(value).isValid() && value ? dayjs(value).format(format) : value
}
