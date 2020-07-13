import Vue from "vue"
import { format, register } from "timeago.js"
import ru from "timeago.js/lib/lang/ru"
register("ru", ru)

const filters = {
  truncate(text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || "..." : "")
  },

  timeAgo(date) {
    return format(date, "ru")
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
