import Vue from "vue"

const filters = {
  truncate(text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || "..." : "")
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
