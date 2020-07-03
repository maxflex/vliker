import Vue from "vue"

const filters = {
  time(value) {
    return value.substring(0, 5)
  },

  nameDefault(user) {
    return [user.first_name, user.last_name].join(" ")
  },

  nameInitials(user) {
    return `${user.last_name} ${user.first_name[0]}.`
  },

  nameLastFirst(user) {
    return [user.last_name, user.first_name].join(" ")
  },

  nameFull(user) {
    return [user.last_name, user.first_name, user.middle_name].join(" ")
  },

  /**
   * Comma-separated list
   */
  list(items, titles = undefined) {
    if (titles !== undefined) {
      return items.map(e => titles[e]).join(", ")
    }
    return items.join(", ")
  },

  truncate(text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || "..." : "")
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
